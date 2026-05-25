<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->unsignedInteger('checkin_radius_meters')->default(50)->after('daily_capacity_limit');
            $table->string('qr_code')->nullable()->after('checkin_radius_meters');
        });
    }

    public function down(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropColumn(['checkin_radius_meters', 'qr_code']);
        });
    }
};
