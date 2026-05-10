<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlotterSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['pending', 'approved', 'rejected'];

        for ($i = 1; $i <= 40; $i++) {
            DB::table('blotters')->insert([
                'user_id' => 1, // change if needed
                'complainant_name' => 'Complainant ' . $i,
                'statement' => 'This is a sample blotter statement number ' . $i . '. ' . Str::random(30),
                'status' => $statuses[array_rand($statuses)],
                'case_number' => 'BTL-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
