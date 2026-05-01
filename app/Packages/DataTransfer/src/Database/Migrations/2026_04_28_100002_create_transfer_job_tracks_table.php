<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_job_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_job_id')->constrained('transfer_jobs')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('state')->default('pending'); // pending | processing | completed | failed
            $table->string('type');
            $table->string('action')->default('append');
            $table->integer('processed_rows_count')->default(0);
            $table->integer('invalid_rows_count')->default(0);
            $table->integer('errors_count')->default(0);
            $table->json('errors')->nullable();
            $table->string('file_path')->nullable();
            $table->string('output_file_path')->nullable();
            $table->string('error_file_path')->nullable();
            $table->json('summary')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_job_tracks');
    }
};
