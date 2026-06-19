<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * First-party page-view analytics for the public site. Records the visit in
 * terminate() (after the response is sent → no page latency). Country/city are
 * resolved via a free IP API, cached per-IP for 30 days so it rarely runs.
 */
class TrackPageVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        try {
            if (! $this->shouldTrack($request, $response)) {
                return;
            }

            $ip = $request->ip();
            $geo = $this->geo($ip);

            PageVisit::create([
                'path' => Str::limit($request->path(), 180, ''),
                'url' => Str::limit($request->fullUrl(), 490, ''),
                'referrer' => Str::limit((string) $request->headers->get('referer'), 490, '') ?: null,
                'ip_address' => $ip,
                'country' => $geo['country'] ?? null,
                'country_code' => $geo['countryCode'] ?? null,
                'city' => $geo['city'] ?? null,
                'device' => $this->device((string) $request->userAgent()),
                'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return false;
        }

        if ($request->expectsJson() || $request->headers->has('X-Inertia')) {
            return false;
        }

        // Skip admin, auth, deploy, assets and non-page endpoints.
        if ($request->is('admin', 'admin/*', 'deploy', 'deploy/*', 'login', 'logout', 'register',
            'password*', 'settings*', 'verify-email*', 'confirm-password',
            'storage/*', 'build/*', 'sitemap.xml', 'robots.txt', 'llms.txt', 'resume/download', 'up')) {
            return false;
        }

        // Skip bots/crawlers so analytics reflect humans.
        $ua = (string) $request->userAgent();
        if ($ua === '' || preg_match('/bot|crawl|spider|slurp|bing|baidu|yandex|duckduck|facebookexternal|embed|preview|monitor|curl|wget|python|headless|lighthouse|gptbot|claudebot|perplexity/i', $ua)) {
            return false;
        }

        return true;
    }

    /** @return array{country?:string,countryCode?:string,city?:string} */
    private function geo(?string $ip): array
    {
        if (! $ip || $ip === '127.0.0.1' || Str::startsWith($ip, ['192.168.', '10.', '172.16.', '::1'])) {
            return [];
        }

        return Cache::remember("geo:{$ip}", now()->addDays(30), function () use ($ip) {
            try {
                $res = Http::timeout(2)->get("http://ip-api.com/json/{$ip}", [
                    'fields' => 'status,country,countryCode,city',
                ])->json();

                return ($res['status'] ?? null) === 'success'
                    ? ['country' => $res['country'] ?? null, 'countryCode' => $res['countryCode'] ?? null, 'city' => $res['city'] ?? null]
                    : [];
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    private function device(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/iPad|Tablet/i', $ua) => 'tablet',
            (bool) preg_match('/Mobi|Android|iPhone/i', $ua) => 'mobile',
            default => 'desktop',
        };
    }
}
