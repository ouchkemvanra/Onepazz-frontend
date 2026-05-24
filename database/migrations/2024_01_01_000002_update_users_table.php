<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->after('id');
            $table->string('full_name_kh')->nullable()->after('full_name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('avatar_url')->nullable()->after('phone');
            $table->enum('preferred_lang', ['en', 'kh'])->default('en')->after('avatar_url');
            $table->enum('preferred_currency', ['usd', 'khr'])->default('usd')->after('preferred_lang');
            $table->date('date_of_birth')->nullable()->after('preferred_currency');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('date_of_birth');
            $table->enum('role', ['member', 'employer_admin', 'gym_admin', 'platform_admin'])->default('member')->after('gender');
            $table->boolean('is_active')->default(true)->after('role');
            $table->softDeletes();

            // Remove the default 'name' column Laravel ships with
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn([
                'full_name', 'full_name_kh', 'phone', 'avatar_url',
                'preferred_lang', 'preferred_currency', 'date_of_birth',
                'gender', 'role', 'is_active', 'deleted_at',
            ]);
        });
    }
};
