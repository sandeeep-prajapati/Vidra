<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostels', function (Blueprint $table) {
            $table->increments('hostel_id');
            $table->string('hostel_name', 100);
            $table->enum('hostel_type', ['Boys', 'Girls', 'Co-ed']);
            $table->integer('total_capacity');
            $table->integer('available_capacity');
            $table->string('location', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostels');
    }
};
