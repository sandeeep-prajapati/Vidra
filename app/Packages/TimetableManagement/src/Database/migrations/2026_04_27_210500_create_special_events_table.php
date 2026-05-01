<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->string('event_name', 100);
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('description')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->timestamps();

            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_events');
    }
};
