<?php

namespace App\Models;

use App\Models\Concerns\Sluggable;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    use Sluggable, Sortable;

    protected $fillable = ['name', 'slug', 'description', 'position', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
