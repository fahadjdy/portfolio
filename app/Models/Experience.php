<?php

namespace App\Models;

use App\Enums\EmploymentType;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use Sortable;

    protected $fillable = [
        'title', 'company', 'company_url', 'location', 'employment_type',
        'start_date', 'end_date', 'is_current', 'description', 'highlights',
        'logo', 'position', 'is_active',
    ];

    protected $casts = [
        'employment_type' => EmploymentType::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'highlights' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
