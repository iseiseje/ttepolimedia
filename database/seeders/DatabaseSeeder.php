<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'username' => 'admin',
        ], [
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        User::updateOrCreate([
            'username' => 'dosen',
        ], [
            'name' => 'Dosen',
            'email' => 'dosen@example.com',
            'password' => Hash::make('dosen'),
            'role' => 'dosen',
        ]);

        User::updateOrCreate([
            'username' => 'guest',
        ], [
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'password' => Hash::make('guest'),
            'role' => 'guest',
        ]);
    }
}
