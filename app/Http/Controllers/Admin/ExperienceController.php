<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EmploymentType;
use App\Models\Experience;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ExperienceController extends CrudController
{
    protected string $model = Experience::class;

    protected string $routeBase = 'admin.experiences';

    protected string $singular = 'Experience';

    protected string $plural = 'Experience';

    protected function transform(Model $item): array
    {
        return array_merge($item->toArray(), [
            'start_date' => $item->start_date?->format('Y-m-d'),
            'end_date' => $item->end_date?->format('Y-m-d'),
            'period' => $item->start_date?->format('M Y').' – '.($item->is_current ? 'Present' : ($item->end_date?->format('M Y') ?? '—')),
        ]);
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'period', 'label' => 'Period'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'title', 'label' => 'Job title', 'type' => 'text', 'required' => true],
            ['name' => 'company', 'label' => 'Company', 'type' => 'text', 'required' => true],
            ['name' => 'company_url', 'label' => 'Company URL', 'type' => 'text'],
            ['name' => 'location', 'label' => 'Location', 'type' => 'text'],
            ['name' => 'employment_type', 'label' => 'Employment type', 'type' => 'select', 'options' => EmploymentType::options(), 'placeholder' => 'Select…'],
            ['name' => 'start_date', 'label' => 'Start date', 'type' => 'date', 'required' => true],
            ['name' => 'end_date', 'label' => 'End date', 'type' => 'date', 'hint' => 'Leave empty if current'],
            ['name' => 'is_current', 'label' => 'Current role', 'type' => 'toggle'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ['name' => 'highlights', 'label' => 'Highlights', 'type' => 'repeater', 'placeholder' => 'Achievement / responsibility'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'company' => ['required', 'string', 'max:160'],
            'company_url' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:160'],
            'employment_type' => ['nullable', 'in:'.implode(',', array_column(EmploymentType::cases(), 'value'))],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['boolean'],
            'description' => ['nullable', 'string'],
            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
