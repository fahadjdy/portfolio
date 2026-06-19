<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Models\Concerns\Sluggable;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use Sluggable, SoftDeletes, Sortable;

    protected $fillable = [
        'title', 'slug', 'client_name', 'category', 'summary',
        'problem', 'solution', 'outcome', 'highlights',
        'live_url', 'repo_url', 'year', 'role', 'duration',
        'status', 'is_featured', 'cover_image', 'thumbnail',
        'seo_title', 'seo_description', 'og_image', 'position',
    ];

    protected $casts = [
        'highlights' => 'array',
        'status' => ProjectStatus::class,
        'is_featured' => 'boolean',
        'year' => 'integer',
    ];

    protected function sluggableSource(): string
    {
        return 'title';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function panels(): HasMany
    {
        return $this->hasMany(ProjectPanel::class)->ordered();
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProjectFeature::class)->ordered();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->ordered();
    }

    public function techTags(): BelongsToMany
    {
        return $this->belongsToMany(TechTag::class)
            ->withPivot('position')
            ->orderBy('pivot_position');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ProjectStatus::Published->value);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
