<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->text('skills')->nullable()->after('profile_completed');
            $table->text('languages')->nullable()->after('skills');
            $table->text('certifications')->nullable()->after('languages');
            $table->string('linkedin_url')->nullable()->after('certifications');
            $table->string('professional_title')->nullable()->after('linkedin_url');
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['skills', 'languages', 'certifications', 'linkedin_url', 'professional_title']);
        });
    }
};
