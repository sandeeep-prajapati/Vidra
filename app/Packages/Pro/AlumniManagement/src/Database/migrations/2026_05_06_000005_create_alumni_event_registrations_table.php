<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('alumni_events')->cascadeOnDelete();
            $table->foreignId('alumni_id')->constrained('alumni_profiles')->cascadeOnDelete();
            $table->timestamp('registered_at')->useCurrent();
            $table->boolean('attended')->default(false);
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'alumni_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_event_registrations');
    }
};
