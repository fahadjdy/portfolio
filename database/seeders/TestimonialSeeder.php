<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'author_name' => 'Operations Lead',
                'author_title' => 'Fitness Studio Owner',
                'company' => 'NTZ Fitness',
                'quote' => 'Fahad delivered a complete gym management system — memberships, payments, ID cards and reminders all in one place. It runs fast and our staff picked it up in a day.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'author_name' => 'Clinic Administrator',
                'author_title' => 'Healthcare Practice',
                'company' => 'WeCare Ortho',
                'quote' => 'The AI treatment-support and patient management system Fahad built saves us real time every day. Thoughtful, reliable engineering.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'author_name' => 'Dealership Manager',
                'author_title' => 'Automotive Business',
                'company' => 'AutoConsult',
                'quote' => 'Our dealer CRM with the lead pipeline transformed how we track inquiries. Conversion is up and nothing slips through the cracks.',
                'rating' => 5,
                'is_featured' => true,
            ],
        ];

        foreach ($testimonials as $i => $t) {
            Testimonial::updateOrCreate(
                ['author_name' => $t['author_name'], 'company' => $t['company']],
                $t + ['position' => $i + 1, 'is_active' => true],
            );
        }
    }
}
