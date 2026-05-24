<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            UserSeeder::class,
            EmployerSeeder::class,
            GymSeeder::class,
            SubscriptionAndEmployeeSeeder::class,
            InvoiceAndPaymentSeeder::class,
            CheckinAndReviewSeeder::class,
        ]);
    }
}