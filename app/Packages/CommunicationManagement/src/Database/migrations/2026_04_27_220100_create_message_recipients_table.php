<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_recipients', function (Blueprint $table) {
            $table->increments('recipient_id');
            $table->unsignedInteger('message_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['Pending', 'Sent', 'Failed', 'Read'])->default('Pending');
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->timestamps();

            $table->foreign('message_id')->references('message_id')->on('messages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_recipients');
    }
};
