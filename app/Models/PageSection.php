<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PageSection extends Model
{
    protected $fillable = [
        'page', 'section_key', 'heading', 'subheading', 'body',
        'image', 'data', 'position', 'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => blank($this->image)
            ? null
            : (str_starts_with($this->image, 'http') ? $this->image : Storage::disk('public')->url($this->image)));
    }
}
