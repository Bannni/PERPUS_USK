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
        // Demo User
        User::firstOrCreate(
            ['email' => 'pembaca@readowl.com'],
            [
                'name' => 'Assya Pembaca',
                'password' => Hash::make('password123'),
            ]
        );

        $this->call([
            BookSeeder::class,
        ]);
    }
}
