<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->enum('interview_type', ['written_exam', 'panel_interview', 'technical_test']);
            $table->dateTime('scheduled_at');
            $table->dateTime('end_time')->nullable();
            $table->string('venue');
            $table->text('instructions')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('interview_panels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['chairperson', 'member', 'secretary']);
            $table->timestamps();
        });

        Schema::create('evaluation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users');
            $table->enum('evaluation_type', ['academic_experience', 'written_exam', 'panel_interview']);
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2)->default(100.00);
            $table->decimal('weight_percentage', 5, 2);
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        Schema::create('offer_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('offer_reference_number')->unique();
            $table->string('position_title');
            $table->string('department');
            $table->string('duty_station');
            $table->decimal('salary_amount', 10, 2);
            $table->string('salary_currency', 3)->default('ETB');
            $table->text('benefits')->nullable();
            $table->date('reporting_date');
            $table->date('offer_expiry_date');
            $table->enum('status', ['draft', 'sent', 'accepted', 'declined', 'expired'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('response_notes')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_letters');
        Schema::dropIfExists('evaluation_scores');
        Schema::dropIfExists('interview_panels');
        Schema::dropIfExists('interviews');
    }
};
