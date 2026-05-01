<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('entity_type');          // student | staff | salary | student_fee
            $table->enum('type', ['import', 'export']);
            $table->string('action')->default('append'); // append | delete
            $table->string('validation_strategy')->default('skip-errors'); // skip-errors | stop-on-errors
            $table->integer('allowed_errors')->default(10);
            $table->string('field_separator')->default(',');
            $table->string('file_path')->nullable();
            $table->json('filters')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_jobs');
    }
};
