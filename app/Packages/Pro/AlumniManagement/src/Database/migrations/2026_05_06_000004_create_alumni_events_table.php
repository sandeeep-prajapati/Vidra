<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->string('venue')->nullable();
            $table->enum('event_type', ['reunion', 'webinar', 'workshop', 'social'])->default('reunion');
            $table->unsignedBigInteger('organizer_alumni_id')->nullable();
            $table->unsignedInteger('max_attendees')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_events');
    }
};
