<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Seed sample data in local/development
        if (app()->environment('local', 'development')) {
            $this->call([
                SampleDataSeeder::class,
            ]);
            
            // Create test user
            if (!User::where('email', 'test@example.com')->exists()) {
                User::factory()->create([
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'phone' => '+251911234571',
                    'user_type' => 'applicant',
                ]);
            }
        }
    }
}
