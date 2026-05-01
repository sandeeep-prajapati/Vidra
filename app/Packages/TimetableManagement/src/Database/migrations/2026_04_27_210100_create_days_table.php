<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('days', function (Blueprint $table) {
            $table->increments('day_id');
            $table->string('day_name', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('days');
    }
};
