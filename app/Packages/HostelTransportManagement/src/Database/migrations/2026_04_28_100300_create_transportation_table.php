<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transportation', function (Blueprint $table) {
            $table->increments('transport_id');
            $table->string('transport_name', 100);
            $table->enum('transport_type', ['Bus', 'Van', 'Shuttle']);
            $table->integer('capacity');
            $table->string('route', 255)->nullable();
            $table->time('departure_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transportation');
    }
};
