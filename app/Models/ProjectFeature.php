<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFeature extends Model
{
    use Sortable;

    protected $fillable = ['project_panel_id', 'project_id', 'title', 'description', 'icon', 'position'];

    protected function sortableGroup(): array
    {
        return ['project_panel_id'];
    }

    public function panel(): BelongsTo
    {
        return $this->belongsTo(ProjectPanel::class, 'project_panel_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
