<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceController extends CrudController
{
    protected string $model = Service::class;

    protected string $routeBase = 'admin.services';

    protected string $singular = 'Service';

    protected string $plural = 'Services';

    protected string $uploadDir = 'services';

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
            ['name' => 'icon', 'label' => 'Icon (lucide name)', 'type' => 'text'],
            ['name' => 'short_description', 'label' => 'Short description', 'type' => 'textarea', 'required' => true],
            ['name' => 'description', 'label' => 'Full description', 'type' => 'textarea', 'rows' => 5],
            ['name' => 'features', 'label' => 'Features', 'type' => 'repeater', 'placeholder' => 'Feature / deliverable'],
            ['name' => 'price_from', 'label' => 'Price from (optional)', 'type' => 'text'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'toggle'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'icon' => ['nullable', 'string', 'max:60'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'price_from' => ['nullable', 'string', 'max:60'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
