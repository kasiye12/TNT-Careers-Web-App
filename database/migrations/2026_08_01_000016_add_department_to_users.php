<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('user_type');
        });
        
        // Add evaluator department to evaluation scores
        Schema::table('evaluation_scores', function (Blueprint $table) {
            $table->string('evaluator_department')->nullable()->after('evaluator_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department');
        });
        Schema::table('evaluation_scores', function (Blueprint $table) {
            $table->dropColumn('evaluator_department');
        });
    }
};
