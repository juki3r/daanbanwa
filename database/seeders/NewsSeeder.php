<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {

            $titles = [
                "Barangay Clean-Up Drive",
                "Free Medical Mission",
                "Disaster Preparedness Training",
                "Road Repair Update",
                "Scholarship Program Announcement",
                "Anti-Drug Awareness Campaign",
                "Tree Planting Activity",
                "Health Check-Up Program",
                "Livelihood Training for Residents",
                "Peace and Order Report",
                "Flood Control Project Update",
                "Senior Citizen Assistance Program",
                "Youth Sports Festival",
                "Waste Management Awareness Drive",
                "Installation of Street Lights"
            ];

            $title = $titles[array_rand($titles)] . " #" . $i;

            DB::table('news')->insert([
                'title' => $title,
                'content' => "Barangay update: {$title}. Residents are encouraged to stay informed and participate in community programs.",
                'image' => null,
                'published_at' => Carbon::now()->subDays(rand(0, 365)),
                'status' => collect(['draft', 'published', 'archived'])->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
