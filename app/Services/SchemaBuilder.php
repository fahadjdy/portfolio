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
            'sameAs' => $this->sameAs(),
            'knowsAbout' => TechTag::active()->ordered()->pluck('name')->take(20)->values()->all(),
        ]);
    }

    /**
     * Off-site entity alignment: the authoritative external profiles that
     * disambiguate this person/brand for search and answer engines. Merges the
     * managed social links with an optional settings-driven list (newline/comma
     * separated) so profiles like LinkedIn, GitHub, Stack Overflow, Crunchbase
     * or Wikidata can be added without a code change.
     *
     * @return array<int, string>
     */
    private function sameAs(): array
    {
        $social = SocialLink::active()->ordered()->pluck('url');
        $extra = collect(preg_split('/[\s,]+/', (string) settings('sameas_profiles'), -1, PREG_SPLIT_NO_EMPTY));

        return $social->merge($extra)
            ->map(fn ($url) => trim((string) $url))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * A SpeakableSpecification (the "Speaker"/voice schema): the CSS selectors
     * whose text answer engines and voice assistants may read aloud.
     *
     * @param array<int, string> $cssSelector
     */
    public function speakable(array $cssSelector = ['h1', '.speakable']): array
    {
        return [
            '@type' => 'SpeakableSpecification',
            'cssSelector' => array_values($cssSelector),
        ];
    }

    /**
     * A WebPage node tying the current page to the site + person entity and
     * carrying the speakable spec. $type lets callers use a more specific
     * subtype (AboutPage, ContactPage, CollectionPage…).
     *
     * @param array<int, string> $speakable
     */
    public function webPage(string $url, ?string $name = null, array $speakable = ['h1', '.speakable'], string $type = 'WebPage'): array
    {
        $home = url('/');

        return array_filter([
            '@type' => $type,
            '@id' => $url.'#webpage',
            'url' => $url,
            'name' => $name,
            'isPartOf' => ['@id' => $home.'/#website'],
            'about' => ['@id' => $home.'/#person'],
            'inLanguage' => 'en',
            'speakable' => $this->speakable($speakable),
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

        return $items ? [
            '@type' => 'FAQPage',
            'mainEntity' => $items,
            'speakable' => $this->speakable(['.faq-q', '.faq-a']),
        ] : null;
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
            'speakable' => $this->speakable(['h1', '.speakable']),
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
            'speakable' => $this->speakable(['h1', '.speakable']),
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
            'speakable' => $this->speakable(['h1', '.prose-content']),
        ]);
    }
}
