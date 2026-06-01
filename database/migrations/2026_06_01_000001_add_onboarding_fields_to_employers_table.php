<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->string('company_size')->nullable()->after('industry');
            $table->string('reference_code')->nullable()->unique()->after('notes');
            $table->string('source')->default('admin')->after('reference_code'); // admin|self_registered|invitation
        });
    }

    public function down(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn(['company_size', 'reference_code', 'source']);
        });
    }
};
