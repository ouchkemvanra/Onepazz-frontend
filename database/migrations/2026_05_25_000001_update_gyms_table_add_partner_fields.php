<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->decimal('monthly_fee_usd', 10, 2)->default(0.00)->after('partner_since');
            $table->decimal('revenue_share_pct', 5, 2)->default(30.00)->after('monthly_fee_usd');
            $table->unsignedInteger('daily_capacity_limit')->nullable()->after('revenue_share_pct');
        });
    }

    public function down(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropColumn(['monthly_fee_usd', 'revenue_share_pct', 'daily_capacity_limit']);
        });
    }
};
