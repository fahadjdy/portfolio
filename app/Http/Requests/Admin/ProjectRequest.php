<?php

namespace App\Http\Requests\Admin;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        $projectId = $this->route('project');

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('projects', 'slug')->ignore($projectId)],
            'client_name' => ['nullable', 'string', 'max:160'],
            'category' => ['nullable', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:500'],
            'problem' => ['nullable', 'string'],
            'solution' => ['nullable', 'string'],
            'outcome' => ['nullable', 'string'],
            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['nullable', 'string', 'max:255'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'role' => ['nullable', 'string', 'max:120'],
            'duration' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:'.implode(',', array_column(ProjectStatus::cases(), 'value'))],
            'is_featured' => ['boolean'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'tech_tag_ids' => ['nullable', 'array'],
            'tech_tag_ids.*' => ['integer', 'exists:tech_tags,id'],
            'panels' => ['nullable', 'array'],
            'panels.*.name' => ['required', 'string', 'max:160'],
            'panels.*.icon' => ['nullable', 'string', 'max:60'],
            'panels.*.description' => ['nullable', 'string', 'max:500'],
            'panels.*.features' => ['nullable', 'array'],
            'panels.*.features.*.title' => ['required', 'string', 'max:200'],
            'panels.*.features.*.description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
