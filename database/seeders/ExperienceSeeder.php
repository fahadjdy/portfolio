<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $experiences = [
            [
                'title' => 'Senior Full-Stack Developer',
                'company' => 'ZOBI Web Solutions',
                'company_url' => 'https://zobiwebsolutions.com',
                'location' => 'India',
                'employment_type' => 'full_time',
                'start_date' => '2022-06-01',
                'end_date' => null,
                'is_current' => true,
                'description' => 'Lead the design and delivery of production SaaS platforms, CRMs and management systems on Laravel + Vue, owning architecture, performance and deployment end to end.',
                'highlights' => [
                    'Architected multi-tenant SaaS platforms (school, gym, dealer CRM) with role-based access control.',
                    'Built AI-assisted products integrating Groq/LLaMA with RAG and tool-calling.',
                    'Set up CI/CD pipelines (GitHub Actions → FTPS) for reliable shared-hosting deployments.',
                    'Optimized page loads and database queries for fast, scalable public-facing sites.',
                ],
            ],
            [
                'title' => 'Full-Stack Developer',
                'company' => 'Freelance / Contract',
                'company_url' => null,
                'location' => 'Remote',
                'employment_type' => 'freelance',
                'start_date' => '2019-01-01',
                'end_date' => '2022-05-31',
                'is_current' => false,
                'description' => 'Delivered custom web applications, invoicing systems and business websites for clients across multiple industries.',
                'highlights' => [
                    'Shipped invoicing, billing and inventory systems with PDF generation and GST handling.',
                    'Developed dynamic, CMS-driven marketing websites with SEO best practices.',
                    'Integrated payment gateways, OAuth and SMS/WhatsApp notification services.',
                ],
            ],
        ];

        foreach ($experiences as $i => $exp) {
            Experience::updateOrCreate(
                ['title' => $exp['title'], 'company' => $exp['company']],
                $exp + ['position' => $i + 1, 'is_active' => true],
            );
        }
    }
}
