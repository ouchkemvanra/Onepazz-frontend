<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedInteger('checkins')->default(0);
            $table->decimal('units', 10, 2)->default(0);
            $table->decimal('value_per_unit', 10, 2)->default(0);
            $table->decimal('payout_usd', 10, 2)->default(0);
            $table->decimal('onepazz_cut', 10, 2)->default(0);
            $table->decimal('khr_rate', 10, 2)->default(4100);
            $table->decimal('payout_khr', 14, 2)->default(0);
            $table->enum('status', ['estimated', 'confirmed', 'paid'])->default('estimated');
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['gym_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_payouts');
    }
};
