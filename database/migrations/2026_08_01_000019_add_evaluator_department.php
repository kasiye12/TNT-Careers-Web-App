<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('evaluation_scores', 'evaluator_department')) {
            Schema::table('evaluation_scores', function (Blueprint $table) {
                $table->string('evaluator_department')->nullable()->after('evaluator_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('evaluation_scores', 'evaluator_department')) {
            Schema::table('evaluation_scores', function (Blueprint $table) {
                $table->dropColumn('evaluator_department');
            });
        }
    }
};
