<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name_am', 100)->nullable();
            $table->string('father_name_am', 100)->nullable();
            $table->string('grand_father_name_am', 100)->nullable();
            $table->string('first_name_en');
            $table->string('father_name_en');
            $table->string('grand_father_name_en');
            $table->enum('gender', ['male', 'female']);
            $table->date('dob');
            $table->date('dob_ethiopian')->nullable();
            $table->string('nationality')->default('Ethiopian');
            $table->string('national_id_number', 100)->nullable();
            $table->string('passport_number', 100)->nullable();
            $table->string('region', 100);
            $table->string('zone_subcity', 100);
            $table->string('woreda', 50);
            $table->string('kebele_house_no', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->boolean('has_construction_exp')->default(false);
            $table->decimal('total_years_exp', 4, 1)->default(0.0);
            $table->boolean('profile_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
