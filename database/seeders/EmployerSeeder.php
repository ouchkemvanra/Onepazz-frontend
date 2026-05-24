<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employer;
use App\Models\User;

class EmployerSeeder extends Seeder
{
    public function run(): void
    {
        $sokha = User::where('email', 'sokha@smartretail.com.kh')->first();
        $vibol = User::where('email', 'vibol@techhub.com.kh')->first();

        Employer::updateOrCreate(
            ['contact_email' => 'sokha@smartretail.com.kh'],
            [
                'company_name'    => 'Smart Retail Solutions Co., Ltd',
                'company_name_kh' => 'ស្មាតរីតែលសូលុយស្យុន',
                'industry'        => 'Retail & E-Commerce',
                'address_line1'   => 'Building 24, Street 352, BKK1',
                'city'            => 'Phnom Penh',
                'province'        => 'Phnom Penh',
                'contact_name'    => 'Sokha Ros',
                'contact_phone'   => '+85523555001',
                'admin_user_id'   => $sokha?->id,
                'status'          => 'active',
            ]
        );

        Employer::updateOrCreate(
            ['contact_email' => 'vibol@techhub.com.kh'],
            [
                'company_name'    => 'Tech Hub Cambodia Co., Ltd',
                'company_name_kh' => 'តិចហាប់កម្ពុជា',
                'industry'        => 'Technology & Software',
                'address_line1'   => 'Level 5, Canadia Tower, Monivong Blvd',
                'city'            => 'Phnom Penh',
                'province'        => 'Phnom Penh',
                'contact_name'    => 'Vibol Phan',
                'contact_phone'   => '+85523555002',
                'admin_user_id'   => $vibol?->id,
                'status'          => 'active',
            ]
        );
    }
}
