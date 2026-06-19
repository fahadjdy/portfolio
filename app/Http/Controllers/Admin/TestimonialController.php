<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TestimonialController extends CrudController
{
    protected string $model = Testimonial::class;

    protected string $routeBase = 'admin.testimonials';

    protected string $singular = 'Testimonial';

    protected string $plural = 'Testimonials';

    protected string $uploadDir = 'testimonials';

    protected function transform(Model $item): array
    {
        return array_merge($item->toArray(), ['avatar_url' => img_url($item->avatar)]);
    }

    protected function columns(): array
    {
        return [
            ['key' => 'author_name', 'label' => 'Author'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'rating', 'label' => 'Rating'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'author_name', 'label' => 'Author name', 'type' => 'text', 'required' => true],
            ['name' => 'author_title', 'label' => 'Author title / role', 'type' => 'text'],
            ['name' => 'company', 'label' => 'Company', 'type' => 'text'],
            ['name' => 'quote', 'label' => 'Quote', 'type' => 'textarea', 'required' => true, 'rows' => 4],
            ['name' => 'rating', 'label' => 'Rating (1–5)', 'type' => 'number'],
            ['name' => 'project_id', 'label' => 'Related project', 'type' => 'select', 'placeholder' => 'None',
                'options' => Project::orderBy('position')->get(['id', 'title'])->map(fn ($p) => ['value' => $p->id, 'label' => $p->title])],
            ['name' => 'avatar', 'label' => 'Avatar', 'type' => 'image', 'current' => $item?->avatar ? img_url($item->avatar) : null],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'toggle'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'author_name' => ['required', 'string', 'max:120'],
            'author_title' => ['nullable', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:120'],
            'quote' => ['required', 'string', 'max:1500'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
