<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'Backend' => [
                'icon' => 'server',
                'skills' => [
                    ['PHP', 95, 'expert', 7],
                    ['Laravel', 95, 'expert', 6],
                    ['REST APIs', 92, 'expert', 6],
                    ['CodeIgniter 4', 85, 'advanced', 4],
                    ['Eloquent ORM', 93, 'expert', 6],
                    ['Queues & Jobs', 85, 'advanced', 4],
                ],
            ],
            'Frontend' => [
                'icon' => 'layout',
                'skills' => [
                    ['Vue 3', 92, 'expert', 5],
                    ['Inertia.js', 90, 'expert', 4],
                    ['TypeScript', 85, 'advanced', 4],
                    ['Tailwind CSS', 92, 'expert', 5],
                    ['Alpine.js', 85, 'advanced', 3],
                    ['shadcn-vue', 82, 'advanced', 2],
                ],
            ],
            'Databases' => [
                'icon' => 'database',
                'skills' => [
                    ['MySQL', 93, 'expert', 7],
                    ['MariaDB', 90, 'expert', 5],
                    ['Redis', 80, 'advanced', 3],
                    ['Query Optimization', 88, 'advanced', 5],
                ],
            ],
            'DevOps & Tools' => [
                'icon' => 'git-branch',
                'skills' => [
                    ['Git & GitHub', 92, 'expert', 7],
                    ['GitHub Actions (CI/CD)', 88, 'advanced', 4],
                    ['Docker', 80, 'advanced', 3],
                    ['Vite', 85, 'advanced', 3],
                    ['Shared Hosting / cPanel', 90, 'expert', 6],
                    ['Linux', 80, 'advanced', 5],
                ],
            ],
            'AI & Integrations' => [
                'icon' => 'sparkles',
                'skills' => [
                    ['LLM Integration (Groq/OpenAI)', 85, 'advanced', 2],
                    ['RAG & Tool-calling', 80, 'advanced', 2],
                    ['Payment Gateways (Stripe/Razorpay)', 85, 'advanced', 4],
                    ['WhatsApp / SMS APIs', 82, 'advanced', 3],
                    ['OAuth (Google/Social)', 85, 'advanced', 4],
                ],
            ],
        ];

        $catPos = 0;
        foreach ($groups as $name => $group) {
            $category = SkillCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'icon' => $group['icon'], 'position' => ++$catPos, 'is_active' => true],
            );

            foreach ($group['skills'] as $i => [$skill, $proficiency, $level, $years]) {
                Skill::updateOrCreate(
                    ['skill_category_id' => $category->id, 'name' => $skill],
                    [
                        'slug' => Str::slug($skill),
                        'proficiency' => $proficiency,
                        'level' => $level,
                        'years_experience' => $years,
                        'is_featured' => $i < 3,
                        'position' => $i + 1,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
