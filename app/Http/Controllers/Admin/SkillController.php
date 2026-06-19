<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SkillLevel;
use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SkillController extends CrudController
{
    protected string $model = Skill::class;

    protected string $routeBase = 'admin.skills';

    protected string $singular = 'Skill';

    protected string $plural = 'Skills';

    protected function indexQuery()
    {
        return Skill::query()->with('category:id,name')->orderBy('skill_category_id')->orderBy('position');
    }

    protected function transform(Model $item): array
    {
        return array_merge($item->toArray(), ['category' => $item->category?->name]);
    }

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Skill'],
            ['key' => 'category', 'label' => 'Category'],
            ['key' => 'proficiency', 'label' => 'Proficiency', 'suffix' => '%'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'skill_category_id', 'label' => 'Category', 'type' => 'select', 'required' => true,
                'options' => SkillCategory::orderBy('position')->get(['id', 'name'])->map(fn ($c) => ['value' => $c->id, 'label' => $c->name])],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'proficiency', 'label' => 'Proficiency (0–100)', 'type' => 'number'],
            ['name' => 'level', 'label' => 'Level', 'type' => 'select', 'options' => SkillLevel::options(), 'placeholder' => 'Select level…'],
            ['name' => 'years_experience', 'label' => 'Years of experience', 'type' => 'number', 'step' => '0.5'],
            ['name' => 'icon', 'label' => 'Icon', 'type' => 'text'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'toggle'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'skill_category_id' => ['required', 'exists:skill_categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'proficiency' => ['nullable', 'integer', 'between:0,100'],
            'level' => ['nullable', 'in:'.implode(',', array_column(SkillLevel::cases(), 'value'))],
            'years_experience' => ['nullable', 'numeric', 'between:0,50'],
            'icon' => ['nullable', 'string', 'max:60'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
