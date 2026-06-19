<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingsSeeder::class,
            SocialLinksSeeder::class,
            PageSectionSeeder::class,
            SkillSeeder::class,
            TechTagSeeder::class,
            ExperienceSeeder::class,
            EducationSeeder::class,
            ServiceSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            ProjectSeeder::class,
            BlogSeeder::class,
        ]);
    }
}
