<?php

namespace App\Http\Controllers\Admin;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SocialLinkController extends CrudController
{
    protected string $model = SocialLink::class;

    protected string $routeBase = 'admin.social-links';

    protected string $singular = 'Social link';

    protected string $plural = 'Social links';

    protected function columns(): array
    {
        return [
            ['key' => 'platform', 'label' => 'Platform'],
            ['key' => 'url', 'label' => 'URL'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $item): array
    {
        return [
            ['name' => 'platform', 'label' => 'Platform', 'type' => 'text', 'required' => true, 'hint' => 'github, linkedin, mail, globe…'],
            ['name' => 'label', 'label' => 'Label', 'type' => 'text'],
            ['name' => 'url', 'label' => 'URL', 'type' => 'text', 'required' => true],
            ['name' => 'icon', 'label' => 'Icon (lucide/brand name)', 'type' => 'text', 'hint' => 'github, linkedin, mail, globe'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'toggle', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $item): array
    {
        return [
            'platform' => ['required', 'string', 'max:60'],
            'label' => ['nullable', 'string', 'max:60'],
            'url' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:60'],
            'is_active' => ['boolean'],
        ];
    }
}
