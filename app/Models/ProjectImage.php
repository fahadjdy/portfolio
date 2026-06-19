<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectImage extends Model
{
    use Sortable;

    protected $fillable = [
        'project_id', 'disk', 'path', 'path_webp', 'path_thumb', 'path_md',
        'srcset', 'alt', 'caption', 'type', 'width', 'height', 'position',
    ];

    protected $casts = [
        'srcset' => 'array',
        'width' => 'integer',
        'height' => 'integer',
    ];

    protected function sortableGroup(): array
    {
        return ['project_id'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn () => $this->resolveUrl($this->path));
    }

    protected function webpUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resolveUrl($this->path_webp ?: $this->path));
    }

    private function resolveUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return str_starts_with($path, 'http') ? $path : Storage::disk($this->disk ?: 'public')->url($path);
    }
}
