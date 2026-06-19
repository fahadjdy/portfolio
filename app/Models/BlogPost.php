<?php

namespace App\Models;

use App\Models\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use Sluggable, SoftDeletes;

    protected $fillable = [
        'blog_category_id', 'title', 'slug', 'excerpt', 'body', 'cover_image',
        'reading_minutes', 'status', 'published_at', 'seo_title', 'seo_description',
        'og_image', 'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'reading_minutes' => 'integer',
    ];

    protected function sluggableSource(): string
    {
        return 'title';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function techTags(): BelongsToMany
    {
        return $this->belongsToMany(TechTag::class, 'blog_post_tag');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
