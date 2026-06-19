<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function __construct(private SettingsService $settings, private ImageService $images)
    {
    }

    public function index()
    {
        return Inertia::render('admin/Settings', [
            'values' => [
                'site_name' => $this->settings->get('site_name'),
                'tagline' => $this->settings->get('tagline'),
                'logo' => img_url($this->settings->get('logo')),
                'favicon' => img_url($this->settings->get('favicon')),
                'meta_title' => $this->settings->get('meta_title'),
                'meta_description' => $this->settings->get('meta_description'),
                'meta_keywords' => $this->settings->get('meta_keywords'),
                'default_title_suffix' => $this->settings->get('default_title_suffix'),
                'og_default_image' => img_url($this->settings->get('og_default_image')),
                'contact_email' => $this->settings->get('contact_email'),
                'contact_phone' => $this->settings->get('contact_phone'),
                'contact_address' => $this->settings->get('contact_address'),
                'whatsapp' => $this->settings->get('whatsapp'),
                'google_analytics_id' => $this->settings->get('google_analytics_id'),
                'blog_enabled' => (bool) $this->settings->get('blog_enabled'),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:160'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'default_title_suffix' => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:190'],
            'contact_phone' => ['nullable', 'string', 'max:60'],
            'contact_address' => ['nullable', 'string', 'max:190'],
            'whatsapp' => ['nullable', 'string', 'max:60'],
            'google_analytics_id' => ['nullable', 'string', 'max:60'],
            'blog_enabled' => ['boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:4096'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,ico,gif', 'max:2048'],
            'og_default_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ]);

        $this->settings->setMany([
            'site_name' => $data['site_name'],
            'tagline' => $data['tagline'] ?? '',
        ], 'general');

        $this->settings->setMany([
            'meta_title' => $data['meta_title'] ?? '',
            'meta_description' => $data['meta_description'] ?? '',
            'meta_keywords' => $data['meta_keywords'] ?? '',
            'default_title_suffix' => $data['default_title_suffix'] ?? '',
        ], 'seo');

        $this->settings->setMany([
            'contact_email' => $data['contact_email'] ?? '',
            'contact_phone' => $data['contact_phone'] ?? '',
            'contact_address' => $data['contact_address'] ?? '',
            'whatsapp' => $data['whatsapp'] ?? '',
            'google_analytics_id' => $data['google_analytics_id'] ?? '',
            'blog_enabled' => (bool) ($data['blog_enabled'] ?? false),
        ], 'contact', ['blog_enabled' => 'boolean']);

        foreach (['logo' => 'general', 'favicon' => 'general', 'og_default_image' => 'seo'] as $field => $group) {
            if ($request->hasFile($field)) {
                $this->settings->set($field, $this->images->storeOptimized($request->file($field), 'branding', 1024), 'image', $group);
            }
        }

        return back()->with('success', 'Settings saved.');
    }
}
