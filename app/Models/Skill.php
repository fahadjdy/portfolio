<?php

namespace App\Models;

use App\Enums\SkillLevel;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use Sortable;

    protected $fillable = [
        'skill_category_id', 'name', 'slug', 'proficiency', 'level',
        'icon', 'years_experience', 'is_featured', 'position', 'is_active',
    ];

    protected $casts = [
        'level' => SkillLevel::class,
        'proficiency' => 'integer',
        'years_experience' => 'float',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected function sortableGroup(): array
    {
        return ['skill_category_id'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'skill_category_id');
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
