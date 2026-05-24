<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('employer_id')->constrained();
            $table->decimal('amount_usd', 10, 2);
            $table->decimal('amount_khr', 14, 2)->nullable();
            $table->string('transfer_reference')->nullable();
            $table->date('transfer_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('receipt_path')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('employer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
