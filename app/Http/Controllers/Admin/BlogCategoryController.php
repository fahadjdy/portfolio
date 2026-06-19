<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BlogCategoryController extends CrudController
{
    protected string $model = BlogCategory::class;

    protected string $routeBase = 'admin.blog-categories';

    protected string $singular = 'Blog category';

    protected string $plural = 'Blog categories';

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    protected function beforeDelete(Model $item): void
    {
        // Posts keep existing; their category FK is set null on delete (migration).
    }
}
