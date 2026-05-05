<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('library_members', function (Blueprint $table) {
            $table->id();
            $table->enum('member_type', ['student', 'staff']);
            $table->unsignedBigInteger('member_id');
            $table->string('membership_number', 50)->unique();
            $table->integer('max_books_allowed')->default(3);
            $table->date('membership_start');
            $table->date('membership_end')->nullable();
            $table->enum('status', ['active', 'suspended', 'expired'])->default('active');
            $table->timestamps();
            $table->index('member_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_members');
    }
};
