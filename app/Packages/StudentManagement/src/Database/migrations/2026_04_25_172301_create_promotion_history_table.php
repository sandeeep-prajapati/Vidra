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
        Schema::create('promotion_history', function (Blueprint $table) {
            $table->increments('promotion_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('from_batch_id');
            $table->unsignedInteger('to_batch_id');
            $table->unsignedInteger('from_academic_year_id')->nullable();
            $table->unsignedInteger('to_academic_year_id')->nullable();
            $table->date('promotion_date');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('from_batch_id')->references('batch_id')->on('batches')->onDelete('cascade');
            $table->foreign('to_batch_id')->references('batch_id')->on('batches')->onDelete('cascade');
            $table->foreign('from_academic_year_id')->references('academic_year_id')->on('academic_years')->onDelete('set null');
            $table->foreign('to_academic_year_id')->references('academic_year_id')->on('academic_years')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_history');
    }
};
