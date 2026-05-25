<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('platform_config')->insertOrIgnore([
            ['key' => 'checkins_per_unit_gold',    'value' => '15', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'checkins_per_unit_silver',  'value' => '20', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'checkins_per_unit_bronze',  'value' => '25', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'revenue_share_pct_default', 'value' => '30', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        DB::table('platform_config')->whereIn('key', [
            'checkins_per_unit_gold',
            'checkins_per_unit_silver',
            'checkins_per_unit_bronze',
            'revenue_share_pct_default',
        ])->delete();
    }
};
