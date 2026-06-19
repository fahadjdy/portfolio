<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'degree' => 'Bachelor of Engineering',
                'field_of_study' => 'Computer / Information Technology',
                'institution' => 'Gujarat Technological University',
                'location' => 'Gujarat, India',
                'start_year' => 2015,
                'end_year' => 2019,
                'grade' => null,
                'description' => 'Studied software engineering, data structures, databases and web technologies.',
            ],
        ];

        foreach ($items as $i => $item) {
            Education::updateOrCreate(
                ['degree' => $item['degree'], 'institution' => $item['institution']],
                $item + ['position' => $i + 1, 'is_active' => true],
            );
        }
    }
}
