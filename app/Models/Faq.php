<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use Sortable;

    protected $fillable = ['scope', 'scope_id', 'question', 'answer', 'position', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected function sortableGroup(): array
    {
        return ['scope', 'scope_id'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeGlobal(Builder $query): Builder
    {
        return $query->where('scope', 'global');
    }

    public function scopeFor(Builder $query, string $scope, ?int $scopeId = null): Builder
    {
        return $query->where('scope', $scope)->where('scope_id', $scopeId);
    }
}
