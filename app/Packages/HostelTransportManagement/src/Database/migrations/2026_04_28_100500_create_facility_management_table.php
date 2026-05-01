<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_management', function (Blueprint $table) {
            $table->increments('facility_id');
            $table->string('facility_name', 100);
            $table->enum('facility_type', ['Sports', 'Library', 'Cafeteria', 'Lab']);
            $table->string('location', 100)->nullable();
            $table->integer('capacity');
            $table->integer('available_capacity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_management');
    }
};
