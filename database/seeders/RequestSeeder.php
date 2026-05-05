<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Request as RequestModel;
use App\Models\User;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->toArray();

        $types = [
            'Barangay Clearance',
            'Certificate of Indigency',
            'Certificate of Residency',
            'Business Clearance',
        ];

        $purposes = [
            'Employment Requirement',
            'Scholarship Application',
            'Valid ID Requirement',
            'Business Permit',
            'Personal Use',
        ];

        for ($i = 0; $i < 100; $i++) {
            RequestModel::create([
                'user_id' => fake()->randomElement($users),
                'full_name' => fake()->name(),
                'age' => fake()->numberBetween(18, 70),
                'gender' => fake()->randomElement(['Male', 'Female']),
                'address' => fake()->address(),
                'document_type' => fake()->randomElement($types),
                'purpose' => fake()->randomElement($purposes),
                'company_name' => fake()->company(),
                'business_nature' => fake()->randomElement([
                    'Sari-Sari Store',
                    'Food Stall',
                    'Internet Cafe',
                    null
                ]),
                'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
                'created_at' => now()->subDays(rand(0, 60)),
                'updated_at' => now(),
            ]);
        }
    }
}
