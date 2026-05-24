<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('employer_subscriptions')->nullOnDelete();
            $table->string('department')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employee_code')->nullable();
            $table->string('membership_card_no')->unique()->nullable();
            $table->date('joined_date')->nullable();
            $table->enum('status', ['active', 'suspended', 'removed'])->default('active');
            $table->timestamp('suspended_at')->nullable();
            $table->string('suspended_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'employer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
