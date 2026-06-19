<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Resume extends Model
{
    protected $fillable = [
        'label', 'file_path', 'original_name', 'mime', 'size', 'is_current', 'downloads',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'size' => 'integer',
        'downloads' => 'integer',
    ];

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn () => blank($this->file_path)
            ? null
            : (str_starts_with($this->file_path, 'http') ? $this->file_path : Storage::disk('public')->url($this->file_path)));
    }
}
