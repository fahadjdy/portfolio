<?php

namespace App\Http\Controllers\Admin;

use App\Models\Education;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class EducationController extends CrudController
{
    protected string $model = Education::class;

    protected string $routeBase = 'admin.education';

    protected string $singular = 'Education entry';

    protected string $plural = 'Education';

    protected function columns(): array
    {
        return [
            ['key' => 'degree', 'label' => 'Degree'],
            ['key' => 'institution', 'label' => 'Institution'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'degree', 'label' => 'Degree', 'type' => 'text', 'required' => true],
            ['name' => 'field_of_study', 'label' => 'Field of study', 'type' => 'text'],
            ['name' => 'institution', 'label' => 'Institution', 'type' => 'text', 'required' => true],
            ['name' => 'location', 'label' => 'Location', 'type' => 'text'],
            ['name' => 'start_year', 'label' => 'Start year', 'type' => 'number', 'required' => true],
            ['name' => 'end_year', 'label' => 'End year', 'type' => 'number'],
            ['name' => 'grade', 'label' => 'Grade', 'type' => 'text'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'degree' => ['required', 'string', 'max:160'],
            'field_of_study' => ['nullable', 'string', 'max:160'],
            'institution' => ['required', 'string', 'max:160'],
            'location' => ['nullable', 'string', 'max:160'],
            'start_year' => ['required', 'integer', 'between:1950,2100'],
            'end_year' => ['nullable', 'integer', 'between:1950,2100', 'gte:start_year'],
            'grade' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
