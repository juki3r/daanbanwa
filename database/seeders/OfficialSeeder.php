<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Official;

class OfficialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officials = [
            [
                'name' => 'Juan Dela Cruz',
                'position' => 'Punong Barangay',
                'assignment' => 'Barangay Captain',
                'phone_number' => '09171234567',
            ],
            [
                'name' => 'Maria Santos',
                'position' => 'Barangay Kagawad',
                'assignment' => 'Committee on Peace and Order',
                'phone_number' => '09181234567',
            ],
            [
                'name' => 'Pedro Reyes',
                'position' => 'Barangay Kagawad',
                'assignment' => 'Committee on Health',
                'phone_number' => '09191234567',
            ],
            [
                'name' => 'Ana Lopez',
                'position' => 'Barangay Kagawad',
                'assignment' => 'Committee on Education',
                'phone_number' => '09201234567',
            ],
            [
                'name' => 'Jose Ramirez',
                'position' => 'Barangay Kagawad',
                'assignment' => 'Committee on Infrastructure',
                'phone_number' => '09211234567',
            ],
            [
                'name' => 'Liza Gomez',
                'position' => 'Barangay Kagawad',
                'assignment' => 'Committee on Women and Family',
                'phone_number' => '09221234567',
            ],
            [
                'name' => 'Ramon Villanueva',
                'position' => 'Barangay Kagawad',
                'assignment' => 'Committee on Environment',
                'phone_number' => '09231234567',
            ],
            [
                'name' => 'Grace Mendoza',
                'position' => 'Barangay Kagawad',
                'assignment' => 'Committee on Youth and Sports',
                'phone_number' => '09241234567',
            ],
            [
                'name' => 'Mark Aquino',
                'position' => 'SK Chairperson',
                'assignment' => 'Sangguniang Kabataan',
                'phone_number' => '09251234567',
            ],
            [
                'name' => 'Nena Bautista',
                'position' => 'Barangay Secretary',
                'assignment' => 'Administrative Services',
                'phone_number' => '09261234567',
            ],
            [
                'name' => 'Carlos Fernandez',
                'position' => 'Barangay Treasurer',
                'assignment' => 'Financial Services',
                'phone_number' => '09271234567',
            ],
        ];

        foreach ($officials as $official) {
            Official::create($official);
        }
    }
}
