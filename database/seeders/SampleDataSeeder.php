<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Applicant;
use App\Models\Vacancy;
use App\Models\EducationHistory;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample applicant if not exists
        $applicantUser = User::updateOrCreate(
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
        if (!$applicantUser->hasRole('applicant')) {
            $applicantUser->syncRoles('applicant');
        }

        $applicant = Applicant::updateOrCreate(
            ['user_id' => $applicantUser->id],
            [
                'first_name_en' => 'Abebe',
                'father_name_en' => 'Kebede',
                'grand_father_name_en' => 'Tadesse',
                'first_name_am' => 'አበበ',
                'father_name_am' => 'ከበደ',
                'grand_father_name_am' => 'ታደሰ',
                'gender' => 'male',
                'dob' => '1990-05-15',
                'nationality' => 'Ethiopian',
                'region' => 'Addis Ababa',
                'zone_subcity' => 'Bole',
                'woreda' => '03',
                'kebele_house_no' => '15/42',
                'city' => 'Addis Ababa',
                'has_construction_exp' => true,
                'total_years_exp' => 8.5,
                'profile_completed' => true,
            ]
        );

        // Add education if not exists
        EducationHistory::firstOrCreate(
            [
                'applicant_id' => $applicant->id,
                'institution' => 'Addis Ababa University',
            ],
            [
                'qualification' => 'bsc',
                'field_of_study' => 'Civil Engineering',
                'cgpa' => 3.45,
                'graduation_year' => 2014,
            ]
        );

        // Add work experience if not exists
        WorkExperience::firstOrCreate(
            [
                'applicant_id' => $applicant->id,
                'organization_name' => 'ABC Construction PLC',
            ],
            [
                'sector' => 'construction',
                'construction_grade' => 'gc_1',
                'is_construction_company' => true,
                'position_held' => 'Site Engineer',
                'project_type' => 'High-rise Building',
                'from_date' => '2015-06-01',
                'to_date' => '2018-08-31',
                'is_current' => false,
                'key_responsibilities' => 'Supervised construction of 15-story commercial building',
                'reason_for_leaving' => 'Project completion',
            ]
        );

        // Create sample vacancies
        $adminId = User::where('email', 'admin@tnt-constructions.com')->first()?->id ?? 1;

        Vacancy::firstOrCreate(
            ['vacancy_number' => 'TNT-VAC-2026-001'],
            [
                'title' => 'Senior Project Engineer',
                'job_category' => 'project_engineering',
                'department' => 'Engineering Department',
                'duty_station_category' => 'project_site',
                'duty_station' => 'Project Site - Building',
                'employment_type' => 'permanent',
                'positions_count' => 2,
                'salary_type' => 'negotiable',
                'min_years_experience' => 8,
                'min_education_level' => 'bsc',
                'construction_experience_required' => true,
                'opening_date' => '2026-08-01',
                'closing_date' => '2026-12-31',
                'status' => 'published',
                'created_by' => $adminId,
            ]
        );

        Vacancy::firstOrCreate(
            ['vacancy_number' => 'TNT-VAC-2026-002'],
            [
                'title' => 'Safety Officer',
                'job_category' => 'occupational_health_safety',
                'department' => 'HSE Department',
                'duty_station_category' => 'project_site',
                'duty_station' => 'Project Site - Road/Bridge',
                'employment_type' => 'contract',
                'positions_count' => 1,
                'salary_type' => 'fixed',
                'salary_amount' => 25000,
                'min_years_experience' => 3,
                'min_education_level' => 'bsc',
                'construction_experience_required' => false,
                'opening_date' => '2026-08-01',
                'closing_date' => '2026-12-31',
                'status' => 'published',
                'description_en' => 'We are looking for a qualified Safety Officer to ensure compliance with occupational health and safety guidelines.',
                'created_by' => $adminId,
            ]
        );

        echo "Sample data seeded successfully!\n";
    }
}
