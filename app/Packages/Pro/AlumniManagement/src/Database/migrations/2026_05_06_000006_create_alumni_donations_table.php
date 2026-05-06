<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni_profiles')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('INR');
            $table->enum('purpose', ['scholarship', 'infrastructure', 'general'])->default('general');
            $table->date('donated_at');
            $table->string('receipt_number')->nullable()->unique();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'refunded'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_donations');
    }
};
