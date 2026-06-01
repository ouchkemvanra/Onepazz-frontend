<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gym_applications', function (Blueprint $table) {
            $table->string('invite_token')->nullable()->unique()->after('notes');
            $table->timestamp('invite_sent_at')->nullable()->after('invite_token');
            $table->timestamp('invite_expires_at')->nullable()->after('invite_sent_at');
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete()->after('invite_expires_at');
            $table->enum('source', ['application', 'invitation'])->default('application')->after('invited_by');
        });
    }

    public function down(): void
    {
        Schema::table('gym_applications', function (Blueprint $table) {
            $table->dropForeign(['invited_by']);
            $table->dropColumn(['invite_token', 'invite_sent_at', 'invite_expires_at', 'invited_by', 'source']);
        });
    }
};
