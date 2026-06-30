<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'SUPER ADMIN',
                'status' => 1,
            ]
        );

        // Create 50 users with varying created_at dates
        for ($i = 0; $i < 50; $i++) {
            $createdAt = now()->subDays(rand(0, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            User::factory()->create([
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // Create 50 books with varying created_at dates
        for ($i = 0; $i < 50; $i++) {
            $createdAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            Book::factory()->create([
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
