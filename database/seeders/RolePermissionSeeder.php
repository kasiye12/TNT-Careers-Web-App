<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage vacancies',
            'view applications',
            'review applications',
            'shortlist candidates',
            'schedule interviews',
            'evaluate candidates',
            'generate offer letters',
            'view reports',
            'manage users',
            'apply for positions',
            'manage profile',
            'upload documents',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $hrRole = Role::create(['name' => 'hr_manager']);
        $hrRole->givePermissionTo([
            'manage vacancies',
            'view applications',
            'review applications',
            'shortlist candidates',
            'schedule interviews',
            'evaluate candidates',
            'generate offer letters',
            'view reports',
        ]);

        $evaluatorRole = Role::create(['name' => 'evaluator']);
        $evaluatorRole->givePermissionTo([
            'view applications',
            'evaluate candidates',
        ]);

        $applicantRole = Role::create(['name' => 'applicant']);
        $applicantRole->givePermissionTo([
            'apply for positions',
            'manage profile',
            'upload documents',
        ]);
    }
}
