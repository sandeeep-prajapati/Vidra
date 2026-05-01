<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('message_id');
            $table->string('title', 150)->nullable();
            $table->text('content');
            $table->enum('message_type', ['SMS', 'Email', 'App Notification', 'Circular', 'Announcement']);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->enum('priority', ['Low', 'Normal', 'High', 'Urgent'])->default('Normal');
            $table->boolean('is_sent')->default(false);
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
