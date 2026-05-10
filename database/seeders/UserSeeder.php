<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $firstNames = [
            'Juan',
            'Maria',
            'Jose',
            'Ana',
            'Pedro',
            'Luisa',
            'Mark',
            'John',
            'Paulo',
            'Grace'
        ];

        $middleNames = [
            'Santos',
            'Reyes',
            'Garcia',
            'Cruz',
            'Torres',
            'Flores',
            'Lopez',
            'Diaz',
            'Ramos',
            'Perez'
        ];

        $lastNames = [
            'Dela Cruz',
            'Santos',
            'Reyes',
            'Garcia',
            'Torres',
            'Flores',
            'Lopez',
            'Diaz',
            'Ramos',
            'Perez'
        ];

        $puroks = [
            'Purok 1',
            'Purok 2',
            'Purok 3',
            'Purok 4',
            'Purok 5'
        ];

        $users = [];

        for ($i = 1; $i <= 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $middleName = $middleNames[array_rand($middleNames)];
            $lastName = $lastNames[array_rand($lastNames)];

            $users[] = [
                'first_name'        => $firstName,
                'middle_name'       => $middleName,
                'last_name'         => $lastName,
                'purok'             => $puroks[array_rand($puroks)],
                'granted'           => rand(0, 1),
                'email'             => "user{$i}@example.com",
                'phone'             => '09' . str_pad((string) rand(0, 999999999), 9, '0', STR_PAD_LEFT),
                'phone_verified'    => rand(0, 1),
                'email_verified_at' => rand(0, 1) ? Carbon::now() : null,
                'password'          => Hash::make('password123'),
                'otp_code'          => null,
                'otp_expires_at'    => null,
                'otp_sent_at'       => null,
                'remember_token'    => Str::random(10),
                'created_at'        => Carbon::now()->subDays(rand(0, 365)),
                'updated_at'        => Carbon::now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
