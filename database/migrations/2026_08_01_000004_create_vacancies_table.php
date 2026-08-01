<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('vacancy_number', 50)->unique();
            $table->string('title');
            $table->enum('job_category', [
                'executive_management',
                'project_engineering',
                'office_engineering',
                'occupational_health_safety',
                'finance_accounting',
                'equipment_logistics',
                'trade_tvet_foremen',
                'other'
            ]);
            $table->string('department', 100);
            $table->enum('duty_station_category', ['head_office', 'project_site']);
            $table->string('duty_station');
            $table->string('specific_location')->nullable();
            $table->enum('employment_type', ['permanent', 'contract', 'project_based', 'temporary']);
            $table->integer('positions_count')->unsigned()->default(1);
            $table->enum('salary_type', ['fixed', 'negotiable', 'scale'])->default('negotiable');
            $table->decimal('salary_amount', 10, 2)->nullable();
            $table->string('salary_currency', 3)->default('ETB');
            $table->integer('min_years_experience')->unsigned()->default(0);
            $table->integer('max_years_experience')->unsigned()->nullable();
            $table->string('required_field_of_study')->nullable();
            $table->decimal('minimum_cgpa', 3, 2)->nullable();
            $table->string('min_education_level');
            $table->boolean('construction_experience_required')->default(false);
            $table->integer('min_construction_years')->unsigned()->nullable();
            $table->date('opening_date');
            $table->date('closing_date');
            $table->text('description_en')->nullable();
            $table->text('description_am')->nullable();
            $table->text('responsibilities_en')->nullable();
            $table->text('responsibilities_am')->nullable();
            $table->text('requirements_en')->nullable();
            $table->text('requirements_am')->nullable();
            $table->enum('status', ['draft', 'published', 'closed', 'archived'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
