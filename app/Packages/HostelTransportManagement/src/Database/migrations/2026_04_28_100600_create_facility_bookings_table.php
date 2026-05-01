<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_bookings', function (Blueprint $table) {
            $table->increments('booking_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedInteger('facility_id');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->foreign('facility_id')->references('facility_id')->on('facility_management')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_bookings');
    }
};
