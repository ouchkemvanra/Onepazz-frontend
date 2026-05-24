<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Employer;
use App\Models\EmployerSubscription;
use App\Models\Employee;
use App\Models\User;

class SubscriptionAndEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $proPlan     = Plan::where('name', 'Professional')->first();
        $smartRetail = Employer::where('contact_email', 'sokha@smartretail.com.kh')->first();
        $techHub     = Employer::where('contact_email', 'vibol@techhub.com.kh')->first();

        $srSub = EmployerSubscription::updateOrCreate(
            ['employer_id' => $smartRetail->id, 'plan_id' => $proPlan->id],
            ['employee_count' => 120, 'start_date' => '2025-06-01', 'status' => 'active', 'billing_cycle' => 'monthly']
        );

        $thSub = EmployerSubscription::updateOrCreate(
            ['employer_id' => $techHub->id, 'plan_id' => $proPlan->id],
            ['employee_count' => 60, 'start_date' => '2025-09-01', 'status' => 'active', 'billing_cycle' => 'monthly']
        );

        $members = [
            ['email' => 'sokha@smartretail.com.kh',  'employer' => $smartRetail, 'sub' => $srSub, 'dept' => 'Marketing',   'title' => 'Marketing Director', 'card' => 'KF-2026-PRO-00001'],
            ['email' => 'dara@smartretail.com.kh',   'employer' => $smartRetail, 'sub' => $srSub, 'dept' => 'Engineering', 'title' => 'Senior Developer',   'card' => 'KF-2026-PRO-00002'],
            ['email' => 'chenda@smartretail.com.kh', 'employer' => $smartRetail, 'sub' => $srSub, 'dept' => 'HR',          'title' => 'HR Manager',         'card' => 'KF-2026-PRO-00003'],
            ['email' => 'vibol@techhub.com.kh',      'employer' => $techHub,     'sub' => $thSub, 'dept' => 'Executive',   'title' => 'CEO',                'card' => 'KF-2026-PRO-00004'],
            ['email' => 'sreymom@techhub.com.kh',    'employer' => $techHub,     'sub' => $thSub, 'dept' => 'Product',     'title' => 'Product Manager',    'card' => 'KF-2026-PRO-00005'],
        ];

        foreach ($members as $m) {
            $user = User::where('email', $m['email'])->first();
            if (!$user) continue;
            Employee::updateOrCreate(
                ['user_id' => $user->id, 'employer_id' => $m['employer']->id],
                [
                    'subscription_id'    => $m['sub']->id,
                    'department'         => $m['dept'],
                    'job_title'          => $m['title'],
                    'membership_card_no' => $m['card'],
                    'status'             => 'active',
                    'joined_date'        => now()->subMonths(rand(3, 12)),
                ]
            );
        }
    }
}
