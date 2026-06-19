<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinksSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            ['platform' => 'github', 'label' => 'GitHub', 'url' => 'https://github.com/fahadjdy', 'icon' => 'github'],
            ['platform' => 'linkedin', 'label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/fahad-jadiya', 'icon' => 'linkedin'],
            ['platform' => 'email', 'label' => 'Email', 'url' => 'mailto:fahadjdy12@gmail.com', 'icon' => 'mail'],
            ['platform' => 'website', 'label' => 'Website', 'url' => 'https://fahad-jadiya.com', 'icon' => 'globe'],
        ];

        foreach ($links as $i => $link) {
            SocialLink::updateOrCreate(
                ['platform' => $link['platform']],
                $link + ['position' => $i + 1, 'is_active' => true],
            );
        }
    }
}
