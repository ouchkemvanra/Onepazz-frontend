<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gyms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_kh')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_kh')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->json('photo_urls')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->default('Phnom Penh');
            $table->string('province')->default('Phnom Penh');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('activity_types')->nullable();
            $table->json('amenities')->nullable();
            $table->enum('tier', ['bronze', 'silver', 'gold'])->default('bronze');
            $table->json('operating_hours')->nullable();
            $table->foreignId('admin_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'active', 'suspended', 'rejected'])->default('pending');
            $table->date('partner_since')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('review_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['city', 'status']);
            $table->index(['tier', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gyms');
    }
};
