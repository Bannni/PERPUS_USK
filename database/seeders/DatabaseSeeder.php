<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Account
        User::updateOrCreate(
            ['email' => 'admin@readowl.com'],
            [
                'name' => 'Administrator Readowl',
                'phone' => '081234567890',
                'password' => Hash::make('Password@123'),
                'role' => 'admin',
            ]
        );

        // 2. Demo Reader User
        User::updateOrCreate(
            ['email' => 'pembaca@readowl.com'],
            [
                'name' => 'Assya Pembaca',
                'phone' => '089876543210',
                'password' => Hash::make('Password@123'),
                'role' => 'user',
            ]
        );

        $this->call([
            BookSeeder::class,
        ]);
    }
}
