<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Custom Web Application Development',
                'icon' => 'code',
                'short_description' => 'End-to-end web apps built on Laravel + Vue — from architecture to deployment.',
                'description' => 'I design and build production-grade web applications tailored to your business, with clean architecture, server-side validation and scalable database design.',
                'features' => ['Laravel + Vue/Inertia', 'Clean, maintainable architecture', 'Server & client-side validation', 'Scalable MySQL schema design'],
                'is_featured' => true,
            ],
            [
                'title' => 'SaaS & Management Systems',
                'icon' => 'layout-dashboard',
                'short_description' => 'Multi-role, multi-tenant SaaS platforms with admin panels and dashboards.',
                'description' => 'School, gym, clinic, dealer and business management systems with role-based access, reporting dashboards and subscription handling.',
                'features' => ['Multi-tenant architecture', 'Role-based access control', 'Analytics dashboards', 'Subscription & billing'],
                'is_featured' => true,
            ],
            [
                'title' => 'CRM & Lead Pipeline Development',
                'icon' => 'users',
                'short_description' => 'Kanban lead pipelines, scoring, follow-ups and sales reporting.',
                'description' => 'Custom CRMs with multi-stage pipelines, lead scoring, activity logs, appointments and conversion analytics.',
                'features' => ['Kanban pipelines', 'Lead scoring & assignment', 'Activity timelines', 'Sales reports & funnels'],
                'is_featured' => true,
            ],
            [
                'title' => 'AI Integration & Automation',
                'icon' => 'sparkles',
                'short_description' => 'LLM-powered assistants, RAG and tool-calling integrated into your product.',
                'description' => 'Integrate Groq/OpenAI-compatible models with retrieval-augmented generation, tool-calling and grounded suggestions — like AI case assistants and treatment decision support.',
                'features' => ['LLM chat assistants', 'RAG over your data', 'Agentic tool-calling', 'Grounded, cited responses'],
                'is_featured' => true,
            ],
            [
                'title' => 'E-commerce & B2B Platforms',
                'icon' => 'shopping-cart',
                'short_description' => 'Catalogs, dynamic pricing engines, carts and order workflows.',
                'description' => 'B2B and marketplace platforms with hierarchical catalogs, tiered/dynamic pricing, carts and multi-stage order workflows.',
                'features' => ['Product catalogs', 'Dynamic/tiered pricing', 'Cart & checkout', 'Order state machines'],
                'is_featured' => false,
            ],
            [
                'title' => 'Invoicing, PDF & Reporting Systems',
                'icon' => 'file-text',
                'short_description' => 'Quotations, GST invoicing, PDF generation and financial analytics.',
                'description' => 'Invoicing and billing systems with quotations, GST handling, tiered pricing, PDF/print output and financial-year reporting.',
                'features' => ['Quotations & invoices', 'GST & tax handling', 'PDF / print output', 'Revenue analytics'],
                'is_featured' => false,
            ],
            [
                'title' => 'API Development & Integrations',
                'icon' => 'plug',
                'short_description' => 'REST APIs, payment gateways, OAuth, SMS/WhatsApp integrations.',
                'description' => 'Secure REST APIs (Sanctum) plus integrations for payments (Stripe/Razorpay), OAuth login, and SMS/WhatsApp notifications.',
                'features' => ['REST APIs (Sanctum)', 'Stripe / Razorpay', 'Google / Social OAuth', 'SMS / WhatsApp'],
                'is_featured' => false,
            ],
            [
                'title' => 'High-Performance Websites & SEO',
                'icon' => 'gauge',
                'short_description' => 'Fast, server-rendered, SEO/AEO/GEO-optimized marketing sites.',
                'description' => 'Server-rendered, fully dynamic marketing sites optimized for Core Web Vitals, structured data, and answer/generative engines.',
                'features' => ['Server-side rendering', 'Core Web Vitals tuning', 'Schema.org / JSON-LD', 'AEO & GEO ready'],
                'is_featured' => false,
            ],
        ];

        foreach ($services as $i => $service) {
            Service::updateOrCreate(
                ['slug' => Str::slug($service['title'])],
                $service + ['position' => $i + 1, 'is_active' => true],
            );
        }
    }
}
