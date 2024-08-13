<?php

namespace Database\Seeders;

use App\Models\Proyek;
use App\Models\Tugas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class proyek_tugas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proyek = Proyek::create([
            'code' => 'PRY001',
            'name' => 'BUILDING APPS CLINIC',
            'description' => 'Building Apps Clinic is a specialized service focused on creating, developing, and optimizing applications tailored for healthcare and clinic management. From patient scheduling and electronic health records to billing and telemedicine, we provide end-to-end solutions that streamline operations, enhance patient care, and ensure compliance with healthcare standards. Our expertise ensures that clinics can operate efficiently with intuitive, secure, and scalable apps that meet the unique needs of healthcare providers.',
            'customer' => 'PT ILHAM SEJATI',
        ]);

        Tugas::create([
            'proyek_id' => $proyek->id,
            'name' => 'CREATE API AUTH WITH JWT',
            'description' => 'Create login , register , reset password with jwt in laravel 11',
            'user_id' => 1,
            'status' => 'waiting',
        ]);

        Tugas::create([
            'proyek_id' => $proyek->id,
            'name' => 'CREATE CRUD USERS',
            'description' => 'CREATE CRUD API WITH MIDDLEWARE JWT',
            'user_id' => 1,
            'status' => 'waiting',
        ]);

        Tugas::create([
            'proyek_id' => $proyek->id,
            'name' => 'CREATE FRONTEND AUTH WITH EXPRESS',
            'description' => 'Create frontend with express , example in Figma',
            'user_id' => 2,
            'status' => 'waiting',
        ]);
    }
}
