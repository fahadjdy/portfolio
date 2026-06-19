<?php

namespace App\Http\Controllers\Admin;

use App\Models\SkillCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SkillCategoryController extends CrudController
{
    protected string $model = SkillCategory::class;

    protected string $routeBase = 'admin.skill-categories';

    protected string $singular = 'Skill category';

    protected string $plural = 'Skill categories';

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'icon', 'label' => 'Icon'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'icon', 'label' => 'Icon (lucide name)', 'type' => 'text', 'hint' => 'e.g. server, layout, database'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'icon' => ['nullable', 'string', 'max:60'],
            'is_active' => ['boolean'],
        ];
    }

    protected function beforeDelete(Model $item): void
    {
        $item->skills()->delete();
    }
}
