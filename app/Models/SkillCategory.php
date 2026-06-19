<?php

namespace App\Models;

use App\Models\Concerns\Sluggable;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillCategory extends Model
{
    use Sluggable, Sortable;

    protected $fillable = ['name', 'slug', 'icon', 'position', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->ordered();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
