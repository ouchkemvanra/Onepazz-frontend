<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employer_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('invite_token')->unique();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('company_name')->nullable();
            $table->foreignId('suggested_plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->text('personal_message')->nullable();
            $table->foreignId('invited_by')->constrained('users');
            $table->timestamp('invite_sent_at')->useCurrent();
            $table->timestamp('invite_expires_at');
            $table->enum('status', ['pending', 'accepted', 'expired'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_invitations');
    }
};
