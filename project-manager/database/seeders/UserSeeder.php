<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@projeto.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(), 
        ]);

        User::factory(3)->create();
    }
}
