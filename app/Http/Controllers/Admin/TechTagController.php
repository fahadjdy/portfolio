<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TechCategory;
use App\Models\TechTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TechTagController extends CrudController
{
    protected string $model = TechTag::class;

    protected string $routeBase = 'admin.tech-tags';

    protected string $singular = 'Tech tag';

    protected string $plural = 'Tech stack';

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'category', 'label' => 'Category'],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => TechCategory::options(), 'placeholder' => 'Select…'],
            ['name' => 'icon', 'label' => 'Icon', 'type' => 'text'],
            ['name' => 'color', 'label' => 'Color (hex)', 'type' => 'text'],
            ['name' => 'proficiency', 'label' => 'Proficiency (0–100)', 'type' => 'number'],
            ['name' => 'is_featured', 'label' => 'Featured (show in public tech stack)', 'type' => 'toggle'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('tech_tags', 'name')->ignore($item?->id)],
            'category' => ['nullable', 'in:'.implode(',', array_column(TechCategory::cases(), 'value'))],
            'icon' => ['nullable', 'string', 'max:60'],
            'color' => ['nullable', 'string', 'max:20'],
            'proficiency' => ['nullable', 'integer', 'between:0,100'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
