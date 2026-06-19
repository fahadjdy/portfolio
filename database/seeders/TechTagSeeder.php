<?php

namespace Database\Seeders;

use App\Models\TechTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechTagSeeder extends Seeder
{
    public function run(): void
    {
        // [name, category, featured]
        $tags = [
            ['PHP', 'language', true],
            ['JavaScript', 'language', false],
            ['TypeScript', 'language', true],
            ['Laravel', 'framework', true],
            ['Vue.js', 'framework', true],
            ['Inertia.js', 'framework', true],
            ['Alpine.js', 'framework', false],
            ['CodeIgniter 4', 'framework', false],
            ['Livewire', 'framework', false],
            ['Tailwind CSS', 'frontend', true],
            ['shadcn-vue', 'frontend', false],
            ['Pinia', 'frontend', false],
            ['Bootstrap', 'frontend', false],
            ['Vite', 'frontend', false],
            ['MySQL', 'database', true],
            ['MariaDB', 'database', false],
            ['Redis', 'database', false],
            ['Docker', 'devops', true],
            ['GitHub Actions', 'devops', false],
            ['cPanel / FTPS', 'devops', false],
            ['Spatie Permission', 'tool', false],
            ['Laravel Sanctum', 'tool', false],
            ['DomPDF', 'tool', false],
            ['Laravel Excel', 'tool', false],
            ['Intervention Image', 'tool', false],
            ['AmCharts', 'tool', false],
            ['Groq / LLaMA 3.3', 'ai', true],
            ['OpenAI-compatible API', 'ai', false],
            ['RAG', 'ai', false],
            ['Stripe', 'tool', false],
            ['Razorpay', 'tool', false],
        ];

        foreach ($tags as $i => [$name, $category, $featured]) {
            TechTag::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'category' => $category,
                    'is_featured' => $featured,
                    'position' => $i + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
