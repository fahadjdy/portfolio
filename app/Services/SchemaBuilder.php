<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\TechTag;
use Illuminate\Support\Str;

/**
 * Builds schema.org JSON-LD as a single connected @graph per page (stable @ids),
 * powering GEO (generative-engine optimization) and rich results.
 */
class SchemaBuilder
{
    public function graph(array $nodes): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter($nodes)),
        ];
    }

    public function person(): array
    {
        $url = url('/');

        return array_filter([
            '@type' => 'Person',
            '@id' => $url.'/#person',
            'name' => settings('site_name', 'Fahad Jadiya'),
            'jobTitle' => settings('tagline', 'Senior Full-Stack Developer'),
            'url' => $url,
            'email' => settings('contact_email'),
            'image' => setting_image('og_default_image'),
            'sameAs' => SocialLink::active()->ordered()->pluck('url')->filter()->values()->all(),
            'knowsAbout' => TechTag::active()->ordered()->pluck('name')->take(20)->values()->all(),
        ]);
    }

    public function website(): array
    {
        $url = url('/');

        return [
            '@type' => 'WebSite',
            '@id' => $url.'/#website',
            'url' => $url,
            'name' => settings('site_name', 'Fahad Jadiya'),
            'publisher' => ['@id' => $url.'/#person'],
            'inLanguage' => 'en',
        ];
    }

    /** @param array<int, array{0:string,1:string}> $items [name, url] */
    public function breadcrumb(array $items): array
    {
        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn ($it, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $it[0],
                'item' => $it[1],
            ])->all(),
        ];
    }

    public function faqPage(iterable $faqs): ?array
    {
        $items = collect($faqs)->map(fn ($f) => [
            '@type' => 'Question',
            'name' => data_get($f, 'question'),
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => data_get($f, 'answer')],
        ])->filter(fn ($q) => filled($q['name']))->values()->all();

        return $items ? ['@type' => 'FAQPage', 'mainEntity' => $items] : null;
    }

    public function project(Project $project): array
    {
        $url = route('projects.show', $project);
        $isApp = filled($project->live_url);

        return array_filter([
            '@type' => $isApp ? 'SoftwareApplication' : 'CreativeWork',
            '@id' => $url.'#project',
            'name' => $project->title,
            'url' => $url,
            'applicationCategory' => $isApp ? 'BusinessApplication' : null,
            'operatingSystem' => $isApp ? 'Web' : null,
            'description' => $project->summary,
            'datePublished' => $project->year ? (string) $project->year : null,
            'image' => $project->cover_image ? img_url($project->cover_image) : null,
            'keywords' => $project->techTags->pluck('name')->implode(', ') ?: null,
            'featureList' => $project->relationLoaded('panels')
                ? $project->panels->flatMap->features->pluck('title')->take(25)->values()->all()
                : null,
            'author' => ['@id' => url('/').'/#person'],
            'sameAs' => $project->live_url ?: null,
        ]);
    }

    public function service(Service $service): array
    {
        $url = route('services.show', $service);

        return [
            '@type' => 'Service',
            '@id' => $url.'#service',
            'name' => $service->title,
            'url' => $url,
            'serviceType' => $service->title,
            'description' => $service->short_description,
            'provider' => ['@id' => url('/').'/#person'],
            'areaServed' => 'Worldwide',
        ];
    }

    public function article(BlogPost $post): array
    {
        $url = route('blog.show', $post);

        return array_filter([
            '@type' => 'BlogPosting',
            '@id' => $url.'#article',
            'headline' => $post->title,
            'url' => $url,
            'description' => $post->excerpt ?: Str::limit(strip_tags((string) $post->body), 155),
            'datePublished' => $post->published_at?->toAtomString(),
            'dateModified' => $post->updated_at?->toAtomString(),
            'image' => $post->cover_image ? img_url($post->cover_image) : null,
            'keywords' => $post->relationLoaded('techTags') ? ($post->techTags->pluck('name')->implode(', ') ?: null) : null,
            'author' => ['@id' => url('/').'/#person'],
            'publisher' => ['@id' => url('/').'/#person'],
            'mainEntityOfPage' => $url,
        ]);
    }
}
