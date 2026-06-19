<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'page' => 'home',
                'section_key' => 'hero',
                'heading' => 'I build fast, scalable web apps & SaaS platforms',
                'subheading' => 'Senior Full-Stack Developer',
                'body' => "Hi, I'm Fahad Jadiya — I design and ship production-grade systems with Laravel, Vue and MySQL: CRMs, management platforms, AI integrations and high-performance marketing sites.",
                'data' => [
                    'rotating_titles' => ['SaaS platforms', 'CRMs & dashboards', 'AI integrations', 'management systems'],
                    'cta_primary' => ['label' => 'View my work', 'href' => '/projects'],
                    'cta_secondary' => ['label' => 'Start a project', 'href' => '/contact'],
                    'stats' => [
                        ['value' => '9+', 'label' => 'Production projects'],
                        ['value' => '7+', 'label' => 'Years experience'],
                        ['value' => '100%', 'label' => 'Full-stack delivery'],
                    ],
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'about_intro',
                'heading' => 'About me',
                'subheading' => 'Senior Full-Stack Developer & DevOps-minded engineer',
                'body' => "I'm a Senior Full-Stack Developer specializing in Laravel and Vue. Over the past 7+ years I've delivered SaaS platforms, CRMs, management systems, invoicing tools and AI-powered products — owning everything from database design and clean architecture to performance optimization and CI/CD deployment. I care about fast, reliable software that doesn't crash and scales with the business.",
                'data' => null,
            ],
            [
                'page' => 'about',
                'section_key' => 'about_main',
                'heading' => 'About Fahad Jadiya',
                'subheading' => 'Senior Full-Stack Developer',
                'body' => "I build production-grade web applications end to end. My core stack is Laravel, Vue 3 (Inertia), TypeScript, Tailwind CSS and MySQL, backed by solid DevOps practices — GitHub Actions CI/CD, Docker, and reliable shared-hosting deployments.\n\nI've shipped multi-tenant SaaS platforms, dealer CRMs with lead pipelines, healthcare and education systems, invoicing tools, and AI-assisted products using LLMs with RAG and tool-calling. I focus on clean architecture, server-side and client-side validation, query optimization, and fast page loads — engineering software that's scalable and dependable.",
                'data' => null,
            ],
        ];

        foreach ($sections as $i => $section) {
            PageSection::updateOrCreate(
                ['page' => $section['page'], 'section_key' => $section['section_key']],
                $section + ['position' => $i + 1, 'is_active' => true],
            );
        }
    }
}
