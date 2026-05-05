<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('library_fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('library_issues')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('library_members')->onDelete('cascade');
            $table->decimal('fine_amount', 10, 2);
            $table->decimal('fine_per_day', 10, 2);
            $table->integer('overdue_days')->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance_amount', 10, 2);
            $table->dateTime('paid_at')->nullable();
            $table->enum('status', ['pending', 'partial', 'paid', 'waived'])->default('pending');
            $table->foreignId('waived_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('waive_reason')->nullable();
            $table->timestamps();
            $table->index('member_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_fines');
    }
};
