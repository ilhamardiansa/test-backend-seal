<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'profile' => 'https://api.dicebear.com/9.x/bottts/png',
            'name' => 'Users',
            'email' => 'user@gmail.com',
            'level' => 'employee',
            'password' => 'user@gmail.com',
        ]);

        User::factory()->create([
            'profile' => 'https://api.dicebear.com/9.x/bottts/png',
            'name' => 'Users2',
            'email' => 'user2@gmail.com',
            'level' => 'employee',
            'password' => 'user2@gmail.com',
        ]);


        User::factory()->create([
            'profile' => 'https://api.dicebear.com/9.x/bottts/png',
            'name' => 'manajer',
            'email' => 'manajer@gmail.com',
            'level' => 'manajer',
            'password' => 'manajer@gmail.com',
        ]);
    }
}
