<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'cv', 'cover_letter', 'degree', 'transcript',
                'license', 'experience_letter', 'clearance_certificate',
                'id_passport', 'other'
            ]);
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type', 50);
            $table->integer('file_size')->unsigned();
            $table->boolean('verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
