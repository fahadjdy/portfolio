<?php

namespace App\Models;

use App\Models\Concerns\Sluggable;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use Sluggable, Sortable;

    protected $fillable = [
        'title', 'slug', 'icon', 'short_description', 'description',
        'features', 'price_from', 'position', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected function sluggableSource(): string
    {
        return 'title';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
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
