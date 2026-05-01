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
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->increments('enrollment_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('batch_id');
            $table->unsignedInteger('academic_year_id')->nullable();
            $table->date('enrollment_date');
            $table->enum('status', ['Active', 'Inactive', 'Graduated', 'Transferred'])->default('Active');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('batch_id')->references('batch_id')->on('batches')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('academic_year_id')->on('academic_years')->onDelete('set null');
            $table->unique(['student_id', 'batch_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};
