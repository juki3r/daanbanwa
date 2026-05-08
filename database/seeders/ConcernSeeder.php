<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Concern;
use App\Models\User;
use Illuminate\Support\Str;

class ConcernSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->toArray();

        if (empty($users)) {
            return;
        }

        $statuses = [
            'submitted',
            'received',
            'under_review',
            'in_progress',
            'resolved',
            'rejected'
        ];

        for ($i = 1; $i <= 50; $i++) {

            Concern::create([
                'user_id' => $users[array_rand($users)],
                'title' => 'Concern #' . $i,
                'location' => 'Location ' . rand(1, 20),
                'description' => 'This is a sample concern description for record #' . $i,
                'status' => $statuses[array_rand($statuses)],
                'progress' => rand(10, 100),
                'admin_reply' => rand(0, 1) ? 'Admin response for concern #' . $i : null,
            ]);
        }
    }
}
