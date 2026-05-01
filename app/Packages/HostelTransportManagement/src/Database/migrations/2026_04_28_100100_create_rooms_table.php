<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->increments('room_id');
            $table->unsignedInteger('hostel_id');
            $table->string('room_number', 10);
            $table->enum('room_type', ['Single', 'Double', 'Triple', 'Quad']);
            $table->integer('capacity');
            $table->integer('occupied')->default(0);
            $table->timestamps();

            $table->foreign('hostel_id')->references('hostel_id')->on('hostels')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_rooms');
    }
};
