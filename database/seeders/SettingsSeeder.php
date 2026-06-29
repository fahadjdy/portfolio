<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = app(SettingsService::class);

        $settings->setMany([
            'site_name' => 'Fahad Jadiya',
            'tagline' => 'Senior Full-Stack Developer',
            'logo' => null,
            'logo_dark' => null,
            'favicon' => null,
            'og_default_image' => null,
        ], group: 'general', types: ['logo' => 'image', 'logo_dark' => 'image', 'favicon' => 'image', 'og_default_image' => 'image']);

        $settings->setMany([
            'meta_title' => 'Fahad Jadiya — Senior Full-Stack Developer (Laravel, Vue, MySQL)',
            'meta_description' => 'Fahad Jadiya is a Senior Full-Stack Developer specializing in Laravel, Vue, and scalable web apps — building SaaS platforms, CRMs, management systems and AI integrations.',
            'meta_keywords' => 'Fahad Jadiya, Senior Full-Stack Developer, Laravel developer, Vue developer, PHP, MySQL, SaaS, CRM, AI integration',
            'twitter_handle' => '',
            'organization_name' => 'Fahad Jadiya',
            'default_title_suffix' => 'Fahad Jadiya',
            'theme_color' => '#4f46e5',
            // Google Search Console site-verification token (the `content` value of
            // the <meta name="google-site-verification"> tag).
            'google_site_verification' => '3dbHcMpmXz4OgN2SwEzWharCkHXxsJJGW69fAoNMRss',
            // Off-site entity alignment: authoritative external profiles (one per line
            // or comma-separated) merged into the Person JSON-LD `sameAs`, e.g.
            // LinkedIn, GitHub, Stack Overflow, Crunchbase, Wikidata.
            'sameas_profiles' => '',
        ], group: 'seo');

        $settings->setMany([
            'contact_email' => 'fahadjdy12@gmail.com',
            'contact_phone' => '',
            'contact_address' => 'India',
            'whatsapp' => '',
            'google_analytics_id' => '',
            'google_adsense_id' => 'ca-pub-5917802205295361',
            'blog_enabled' => true,
        ], group: 'contact', types: ['blog_enabled' => 'boolean']);
    }
}
