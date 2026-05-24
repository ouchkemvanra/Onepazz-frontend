<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'                   => 'Starter',
                'name_kh'                => 'ចាប់ផ្ដើម',
                'description'            => 'Access to 30+ partner gyms. Ideal for small teams.',
                'price_usd'              => 25.00,
                'tier'                   => 'bronze',
                'max_employees'          => 50,
                'gym_checkins_per_month' => 8,
                'features'               => json_encode(['Access to 30+ gyms', 'Up to 50 employees', 'Basic usage reports', 'Email support']),
                'is_active'              => true,
                'display_order'          => 1,
            ],
            [
                'name'                   => 'Professional',
                'name_kh'                => 'វិជ្ជាជីវៈ',
                'description'            => 'Full access to 80+ gyms including Gold-tier studios.',
                'price_usd'              => 45.00,
                'tier'                   => 'silver',
                'max_employees'          => 250,
                'gym_checkins_per_month' => 12,
                'features'               => json_encode(['Access to 80+ gyms', 'Up to 250 employees', 'Advanced analytics', 'CSV & PDF exports', 'Priority support']),
                'is_active'              => true,
                'display_order'          => 2,
            ],
            [
                'name'                   => 'Enterprise',
                'name_kh'                => 'សហគ្រាស',
                'description'            => 'Unlimited access to the entire KhmerFit network.',
                'price_usd'              => 99.00,
                'tier'                   => 'all',
                'max_employees'          => null,
                'gym_checkins_per_month' => 999,
                'features'               => json_encode(['All gyms in network', 'Unlimited employees', 'Custom reporting', 'Dedicated account manager']),
                'is_active'              => true,
                'display_order'          => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
