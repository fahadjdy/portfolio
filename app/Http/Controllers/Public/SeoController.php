<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function sitemap()
    {
        $xml = Cache::remember('seo.sitemap', now()->addHours(6), function () {
            $urls = [
                ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
                ['loc' => route('about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
                ['loc' => route('projects.index'), 'priority' => '0.9', 'changefreq' => 'weekly'],
                ['loc' => route('services.index'), 'priority' => '0.8', 'changefreq' => 'monthly'],
                ['loc' => route('contact'), 'priority' => '0.6', 'changefreq' => 'yearly'],
            ];

            foreach (Project::published()->ordered()->get(['slug', 'updated_at']) as $p) {
                $urls[] = ['loc' => route('projects.show', $p->slug), 'lastmod' => $p->updated_at?->toAtomString(), 'priority' => '0.8', 'changefreq' => 'monthly'];
            }
            foreach (Service::active()->ordered()->get(['slug', 'updated_at']) as $s) {
                $urls[] = ['loc' => route('services.show', $s->slug), 'lastmod' => $s->updated_at?->toAtomString(), 'priority' => '0.6', 'changefreq' => 'monthly'];
            }

            if (settings('blog_enabled')) {
                $urls[] = ['loc' => route('blog.index'), 'priority' => '0.7', 'changefreq' => 'weekly'];
                foreach (BlogPost::published()->orderByDesc('published_at')->get(['slug', 'updated_at']) as $b) {
                    $urls[] = ['loc' => route('blog.show', $b->slug), 'lastmod' => $b->updated_at?->toAtomString(), 'priority' => '0.6', 'changefreq' => 'monthly'];
                }
            }

            return view('seo.sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function robots()
    {
        $lines = [];

        if (app()->environment('production')) {
            $lines[] = 'User-agent: *';
            $lines[] = 'Allow: /';
            $lines[] = 'Disallow: /admin';
            $lines[] = 'Disallow: /deploy';
            $lines[] = 'Disallow: /login';
            $lines[] = '';
            // Explicitly welcome AI / answer-engine crawlers (GEO).
            foreach (['GPTBot', 'ClaudeBot', 'anthropic-ai', 'PerplexityBot', 'Google-Extended', 'CCBot'] as $bot) {
                $lines[] = "User-agent: {$bot}";
                $lines[] = 'Allow: /';
                $lines[] = '';
            }
            $lines[] = 'Sitemap: '.route('sitemap');
        } else {
            // Block indexing of non-production environments.
            $lines[] = 'User-agent: *';
            $lines[] = 'Disallow: /';
        }

        return response(implode("\n", $lines)."\n", 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function llms()
    {
        $content = Cache::remember('seo.llms', now()->addHours(12), function () {
            $name = settings('site_name', 'Fahad Jadiya');
            $tagline = settings('tagline', 'Senior Full-Stack Developer');
            $email = settings('contact_email', 'fahadjdy12@gmail.com');

            $out = [];
            $out[] = "# {$name} — {$tagline}";
            $out[] = '';
            $out[] = "> {$name} is a {$tagline} specializing in Laravel, PHP, Vue, MySQL and Tailwind CSS. "
                .'Builds production SaaS platforms, CRMs, management systems, invoicing tools and AI integrations '
                .'(LLMs with RAG and tool-calling), with strong DevOps practices (CI/CD, Docker, shared-hosting deployment).';
            $out[] = '';

            $out[] = '## Contact';
            $out[] = "- Email: {$email}";
            $out[] = '- Website: '.url('/');
            $out[] = '- Contact form: '.route('contact');
            $out[] = '';

            $out[] = '## Services';
            foreach (Service::active()->ordered()->get() as $s) {
                $out[] = "- [{$s->title}](".route('services.show', $s->slug)."): {$s->short_description}";
            }
            $out[] = '';

            $out[] = '## Featured Projects';
            foreach (Project::published()->ordered()->with('techTags')->get() as $p) {
                $tech = $p->techTags->pluck('name')->take(5)->implode(', ');
                $out[] = "- [{$p->title}](".route('projects.show', $p->slug)."){$this->techSuffix($tech)}: {$p->summary}";
            }
            $out[] = '';

            if (settings('blog_enabled')) {
                $out[] = '## Latest Articles';
                foreach (BlogPost::published()->orderByDesc('published_at')->limit(10)->get() as $b) {
                    $out[] = "- [{$b->title}](".route('blog.show', $b->slug).')'.($b->excerpt ? ': '.$b->excerpt : '');
                }
                $out[] = '';
            }

            $out[] = '## Pages';
            $out[] = '- [About]('.route('about').')';
            $out[] = '- [Projects]('.route('projects.index').')';
            $out[] = '- [Services]('.route('services.index').')';
            $out[] = '- [Contact]('.route('contact').')';
            $out[] = '';

            return implode("\n", $out);
        });

        return response($content, 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function adsTxt()
    {
        $adsenseId = settings('google_adsense_id');

        // No publisher ID configured → no ads.txt (404 so crawlers simply skip it).
        if (! $adsenseId) {
            abort(404);
        }

        // ads.txt needs the bare publisher ID — drop the "ca-" prefix AdSense shows in its UI.
        $pubId = preg_replace('/^ca-/', '', $adsenseId);

        // f08c47fec0942fa0 is Google's fixed certification-authority ID for all AdSense accounts.
        $line = "google.com, {$pubId}, DIRECT, f08c47fec0942fa0";

        return response($line."\n", 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }

    private function techSuffix(string $tech): string
    {
        return $tech ? " ({$tech})" : '';
    }
}
