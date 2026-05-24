<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('employer_id')->constrained();
            $table->foreignId('subscription_id')->constrained('employer_subscriptions');
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->unsignedInteger('employee_count');
            $table->decimal('plan_price_usd', 10, 2);
            $table->decimal('subtotal_usd', 10, 2);
            $table->decimal('tax_usd', 10, 2)->default(0);
            $table->decimal('total_usd', 10, 2);
            $table->decimal('khr_rate', 10, 2);
            $table->decimal('total_khr', 14, 2)->nullable();
            $table->enum('status', ['unpaid', 'pending_verification', 'paid', 'overdue', 'void'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
