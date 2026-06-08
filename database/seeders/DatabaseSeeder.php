<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'abdallahhamada2103@gmail.com'],
            [
                'name' => 'Abdallah Awadallah',
                'password' => bcrypt('12345678'),
                'privilege_level' => 3,
            ]
        );
    }
}
