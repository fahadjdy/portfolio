<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Auto-generates a unique URL slug from a source attribute (default: name).
 * Override sluggableSource() to slug from a different column (e.g. 'title').
 * Respects soft-deletes so a re-used title doesn't collide with a trashed row.
 */
trait Sluggable
{
    public static function bootSluggable(): void
    {
        static::saving(function ($model) {
            if (blank($model->slug) && filled($model->{$model->sluggableSource()})) {
                $model->slug = $model->generateUniqueSlug((string) $model->{$model->sluggableSource()});
            }
        });
    }

    protected function sluggableSource(): string
    {
        return 'name';
    }

    public function generateUniqueSlug(string $value): string
    {
        $base = Str::slug($value) ?: Str::random(8);
        $slug = $base;
        $suffix = 1;

        while ($this->slugExists($slug)) {
            $slug = $base.'-'.(++$suffix);
        }

        return $slug;
    }

    protected function slugExists(string $slug): bool
    {
        $query = static::query()->where('slug', $slug);

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            $query->withTrashed();
        }

        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        return $query->exists();
    }
}
