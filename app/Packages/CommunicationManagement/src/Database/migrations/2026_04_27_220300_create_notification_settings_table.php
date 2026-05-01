<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->increments('setting_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->boolean('allow_sms')->default(true);
            $table->boolean('allow_email')->default(true);
            $table->boolean('allow_app')->default(true);
            $table->boolean('allow_announcements')->default(true);
            $table->boolean('allow_circulars')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
