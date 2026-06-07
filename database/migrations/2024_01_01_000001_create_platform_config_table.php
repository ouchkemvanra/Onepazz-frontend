<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_config', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::table('platform_config')->insert([
            ['key' => 'khr_rate',      'value' => '4100', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'support_email', 'value' => 'support@onepazz.com.kh', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_name',     'value' => 'ACLEDA Bank', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_account',  'value' => '1234-5678-9012-3456', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_holder',   'value' => 'OnePazz Co., Ltd', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_config');
    }
};
