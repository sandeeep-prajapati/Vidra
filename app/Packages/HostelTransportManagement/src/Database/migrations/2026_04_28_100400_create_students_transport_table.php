<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students_transport', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedInteger('transport_id');
            $table->string('pickup_location', 255)->nullable();
            $table->string('drop_location', 255)->nullable();
            $table->date('assigned_date');
            $table->date('leave_date')->nullable();
            $table->timestamps();

            $table->foreign('transport_id')->references('transport_id')->on('transportation')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students_transport');
    }
};
