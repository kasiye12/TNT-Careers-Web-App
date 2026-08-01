<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Use updateOrCreate to avoid duplicate errors
        $admin = User::updateOrCreate(
            ['email' => 'admin@tnt-constructions.com'],
            [
                'name' => 'System Administrator',
                'phone' => '+251911234567',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->syncRoles('admin');
        }

        $hr = User::updateOrCreate(
            ['email' => 'hr@tnt-constructions.com'],
            [
                'name' => 'HR Manager',
                'phone' => '+251911234568',
                'password' => Hash::make('password'),
                'user_type' => 'hr_manager',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        if (!$hr->hasRole('hr_manager')) {
            $hr->syncRoles('hr_manager');
        }

        $evaluator = User::updateOrCreate(
            ['email' => 'engineer@tnt-constructions.com'],
            [
                'name' => 'Senior Engineer',
                'phone' => '+251911234569',
                'password' => Hash::make('password'),
                'user_type' => 'evaluator',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        if (!$evaluator->hasRole('evaluator')) {
            $evaluator->syncRoles('evaluator');
        }

        $applicant = User::updateOrCreate(
            ['email' => 'applicant@example.com'],
            [
                'name' => 'Abebe Kebede',
                'phone' => '+251911234570',
                'password' => Hash::make('password'),
                'user_type' => 'applicant',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        if (!$applicant->hasRole('applicant')) {
            $applicant->syncRoles('applicant');
        }

        echo "Users seeded/updated successfully!\n";
    }
}
