<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained()->onDelete('cascade');
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'draft',
                'submitted',
                'document_verified',
                'shortlisted',
                'written_exam',
                'interview',
                'medical_check',
                'selected',
                'rejected'
            ])->default('submitted');
            $table->boolean('declaration_accepted')->default(false);
            $table->text('rejection_reason')->nullable();
            $table->json('auto_screening_results')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['vacancy_id', 'applicant_id']);
        });

        Schema::create('application_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('changed_by')->constrained('users');
            $table->enum('old_status', [
                'draft', 'submitted', 'document_verified', 'shortlisted',
                'written_exam', 'interview', 'medical_check', 'selected', 'rejected'
            ])->nullable();
            $table->enum('new_status', [
                'draft', 'submitted', 'document_verified', 'shortlisted',
                'written_exam', 'interview', 'medical_check', 'selected', 'rejected'
            ]);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_status_logs');
        Schema::dropIfExists('applications');
    }
};
