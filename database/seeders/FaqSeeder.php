<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What does Fahad Jadiya do?',
                'answer' => 'Fahad Jadiya is a Senior Full-Stack Developer who builds scalable web applications, SaaS platforms, CRMs, management systems and AI integrations using Laravel, Vue, and MySQL.',
            ],
            [
                'question' => 'Which tech stack does Fahad use?',
                'answer' => 'Fahad primarily works with Laravel (PHP), Vue 3 with Inertia.js, TypeScript, Tailwind CSS and MySQL/MariaDB, plus DevOps tooling like GitHub Actions, Docker and Vite. He also integrates AI models (Groq/OpenAI-compatible) with RAG and tool-calling.',
            ],
            [
                'question' => 'Does Fahad build custom SaaS and management systems?',
                'answer' => 'Yes. Fahad has built multi-tenant, multi-role SaaS platforms including school, gym, clinic, dealer CRM and B2B systems — each with role-based access control, dashboards and reporting.',
            ],
            [
                'question' => 'Can Fahad integrate AI into my application?',
                'answer' => 'Yes. Fahad builds AI-assisted features such as chat assistants, document analysis, and grounded suggestions using LLMs (Groq/LLaMA, OpenAI-compatible APIs), retrieval-augmented generation (RAG) and agentic tool-calling.',
            ],
            [
                'question' => 'How long does a typical project take?',
                'answer' => 'Timelines depend on scope. A focused marketing site or MVP can take 2–4 weeks, while a full SaaS platform with admin panels and integrations typically takes 6–12 weeks. Share your requirements for an accurate estimate.',
            ],
            [
                'question' => 'How can I hire Fahad or start a project?',
                'answer' => 'Use the contact form on this site or email fahadjdy12@gmail.com with a short description of your project. Fahad will reply with next steps, scope and an estimate.',
            ],
        ];

        foreach ($faqs as $i => $faq) {
            Faq::updateOrCreate(
                ['scope' => 'global', 'scope_id' => null, 'question' => $faq['question']],
                ['answer' => $faq['answer'], 'position' => $i + 1, 'is_active' => true],
            );
        }
    }
}
