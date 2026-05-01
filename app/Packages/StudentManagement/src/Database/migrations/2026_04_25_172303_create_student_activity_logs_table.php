<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_activity_logs', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('student_id');
            $table->enum('activity_type', [
                'status_change',
                'admission',
                'promotion',
                'enrollment',
                'graduation',
                'transfer',
                'document_upload',
                'health_update',
                'personal_info_update',
                'other',
            ]);
            $table->text('description');
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['student_id', 'activity_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_activity_logs');
    }
};
