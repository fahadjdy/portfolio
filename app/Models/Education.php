<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use Sortable;

    protected $table = 'education';

    protected $fillable = [
        'degree', 'field_of_study', 'institution', 'location',
        'start_year', 'end_year', 'grade', 'description',
        'logo', 'position', 'is_active',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
