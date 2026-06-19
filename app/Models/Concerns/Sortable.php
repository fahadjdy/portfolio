<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Adds drag-sort support via an integer `position` column.
 *
 * On create, position auto-increments to the end (optionally scoped to a group
 * of columns — e.g. skills ordered within their skill_category_id). Use the
 * `ordered()` scope to read records in display order.
 */
trait Sortable
{
    public static function bootSortable(): void
    {
        static::creating(function ($model) {
            if (is_null($model->position)) {
                $query = static::query();
                foreach ($model->sortableGroup() as $column) {
                    $query->where($column, $model->{$column});
                }
                $model->position = (int) $query->max('position') + 1;
            }
        });
    }

    /** Columns that scope the ordering (empty = global ordering). */
    protected function sortableGroup(): array
    {
        return [];
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderBy('id');
    }
}
