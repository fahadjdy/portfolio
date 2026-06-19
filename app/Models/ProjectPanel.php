<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectPanel extends Model
{
    use Sortable;

    protected $fillable = ['project_id', 'name', 'icon', 'description', 'image', 'position'];

    protected function sortableGroup(): array
    {
        return ['project_id'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProjectFeature::class)->ordered();
    }
}
