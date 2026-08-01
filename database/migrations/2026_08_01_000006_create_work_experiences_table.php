<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->string('organization_name');
            $table->enum('sector', ['construction', 'government', 'consultant', 'other']);
            $table->enum('construction_grade', ['gc_1', 'gc_2', 'gc_3', 'gc_4', 'gc_5', 'bc_1', 'bc_2', 'bc_3', 'sc_1', 'other', 'not_applicable'])->nullable();
            $table->boolean('is_construction_company')->default(false);
            $table->string('position_held');
            $table->text('key_responsibilities')->nullable();
            $table->string('project_type')->nullable();
            $table->decimal('project_cost_etb', 15, 2)->nullable();
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->string('reason_for_leaving')->nullable();
            $table->string('experience_letter_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
