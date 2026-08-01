<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->string('institution');
            $table->enum('qualification', ['tvet_level_1', 'tvet_level_2', 'tvet_level_3', 'tvet_level_4', 'tvet_level_5', 'diploma', 'bsc', 'ba', 'msc', 'ma', 'phd']);
            $table->string('field_of_study');
            $table->decimal('cgpa', 3, 2)->nullable();
            $table->integer('graduation_year');
            $table->integer('graduation_year_ec')->nullable();
            $table->string('certificate_file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_histories');
    }
};
