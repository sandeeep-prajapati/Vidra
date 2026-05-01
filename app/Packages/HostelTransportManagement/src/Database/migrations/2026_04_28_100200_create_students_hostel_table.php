<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students_hostel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedInteger('hostel_id');
            $table->unsignedInteger('room_id');
            $table->date('assigned_date');
            $table->date('checkout_date')->nullable();
            $table->timestamps();

            $table->foreign('hostel_id')->references('hostel_id')->on('hostels')->onDelete('cascade');
            $table->foreign('room_id')->references('room_id')->on('hostel_rooms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students_hostel');
    }
};
