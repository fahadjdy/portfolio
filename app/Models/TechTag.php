<?php

namespace App\Models;

use App\Enums\TechCategory;
use App\Models\Concerns\Sluggable;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TechTag extends Model
{
    use Sluggable, Sortable;

    protected $fillable = [
        'name', 'slug', 'icon', 'color', 'category',
        'is_featured', 'proficiency', 'position', 'is_active',
    ];

    protected $casts = [
        'category' => TechCategory::class,
        'is_featured' => 'boolean',
        'proficiency' => 'integer',
        'is_active' => 'boolean',
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withPivot('position');
    }

    public function blogPosts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
