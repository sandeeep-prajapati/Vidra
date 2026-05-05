<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('library_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('library_books')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('library_members')->onDelete('cascade');
            $table->dateTime('reserved_at');
            $table->dateTime('notified_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->enum('status', ['waiting', 'notified', 'cancelled', 'fulfilled'])->default('waiting');
            $table->timestamps();
            $table->index('book_id');
            $table->index('member_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_reservations');
    }
};
