<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed a couple of named demo users (for login + project ownership)
     * plus a handful of random members for issue assignment.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Alice Owner',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Bob Owner',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);

        // Additional random team members available for assignment.
        User::factory()->count(6)->create();
    }
}
