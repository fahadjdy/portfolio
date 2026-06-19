<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\TechTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlogPostController extends CrudController
{
    protected string $model = BlogPost::class;

    protected string $routeBase = 'admin.blog-posts';

    protected string $singular = 'Post';

    protected string $plural = 'Blog posts';

    protected bool $sortable = false;

    protected string $uploadDir = 'blog';

    protected function indexQuery()
    {
        return BlogPost::query()->with('category:id,name')->orderByDesc('published_at')->latest();
    }

    protected function transform(Model $item): array
    {
        return array_merge($item->toArray(), [
            'category' => $item->category?->name,
            'published_at' => $item->published_at?->format('Y-m-d'),
            'tech_tag_ids' => $item->relationLoaded('techTags') ? $item->techTags->pluck('id') : $item->techTags()->pluck('tech_tags.id'),
        ]);
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'category', 'label' => 'Category'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'published_at', 'label' => 'Published'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
            ['name' => 'blog_category_id', 'label' => 'Category', 'type' => 'select', 'placeholder' => 'Uncategorized',
                'options' => BlogCategory::orderBy('position')->get(['id', 'name'])->map(fn ($c) => ['value' => $c->id, 'label' => $c->name])],
            ['name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea', 'hint' => 'Short summary shown on cards / meta (max 500)'],
            ['name' => 'body', 'label' => 'Body', 'type' => 'textarea', 'rows' => 14, 'required' => true, 'hint' => 'Plain text or HTML'],
            ['name' => 'cover_image', 'label' => 'Cover image', 'type' => 'image', 'current' => $item?->cover_image ? img_url($item->cover_image) : null],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true,
                'options' => [['value' => 'draft', 'label' => 'Draft'], ['value' => 'published', 'label' => 'Published']]],
            ['name' => 'published_at', 'label' => 'Publish date', 'type' => 'date', 'hint' => 'Defaults to now when published'],
            ['name' => 'tech_tag_ids', 'label' => 'Tags', 'type' => 'tags', 'relation' => 'techTags',
                'options' => TechTag::orderBy('name')->get(['id', 'name'])->map(fn ($t) => ['value' => $t->id, 'label' => $t->name])],
            ['name' => 'seo_title', 'label' => 'SEO title', 'type' => 'text'],
            ['name' => 'seo_description', 'label' => 'SEO description', 'type' => 'textarea'],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'tech_tag_ids' => ['nullable', 'array'],
            'tech_tag_ids.*' => ['integer', 'exists:tech_tags,id'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:300'],
        ];
    }

    protected function beforeSave(Model $item, Request $request, array $data): void
    {
        $words = str_word_count(strip_tags((string) $item->body));
        $item->reading_minutes = max(1, (int) ceil($words / 200));

        if ($item->status === 'published' && blank($item->published_at)) {
            $item->published_at = Carbon::now();
        }
    }
}
