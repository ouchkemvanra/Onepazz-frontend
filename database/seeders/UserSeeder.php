<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'full_name'          => 'Sokha Ros',
                'full_name_kh'       => 'រស សុខា',
                'email'              => 'sokha@smartretail.com.kh',
                'password'           => Hash::make('password123'),
                'phone'              => '+85512111001',
                'role'               => 'employer_admin',
                'preferred_lang'     => 'en',
                'preferred_currency' => 'usd',
            ],
            [
                'full_name'          => 'Vibol Phan',
                'full_name_kh'       => 'ផាន វីបុល',
                'email'              => 'vibol@techhub.com.kh',
                'password'           => Hash::make('password123'),
                'phone'              => '+85512222001',
                'role'               => 'employer_admin',
                'preferred_lang'     => 'en',
                'preferred_currency' => 'usd',
            ],
            [
                'full_name'          => 'Dara Meas',
                'full_name_kh'       => 'មាស ដារ៉ា',
                'email'              => 'dara@smartretail.com.kh',
                'password'           => Hash::make('password123'),
                'phone'              => '+85512111002',
                'role'               => 'member',
                'preferred_lang'     => 'kh',
                'preferred_currency' => 'khr',
            ],
            [
                'full_name'          => 'Chenda Keo',
                'full_name_kh'       => 'កែវ ចន្ទា',
                'email'              => 'chenda@smartretail.com.kh',
                'password'           => Hash::make('password123'),
                'phone'              => '+85512111003',
                'role'               => 'member',
                'preferred_lang'     => 'en',
                'preferred_currency' => 'usd',
            ],
            [
                'full_name'          => 'Sreymom Uch',
                'full_name_kh'       => 'អ៊ុច ស្រីម៉ុម',
                'email'              => 'sreymom@techhub.com.kh',
                'password'           => Hash::make('password123'),
                'phone'              => '+85512222002',
                'role'               => 'member',
                'preferred_lang'     => 'kh',
                'preferred_currency' => 'khr',
            ],
            [
                'full_name'          => 'OnePazz Admin',
                'full_name_kh'       => null,
                'email'              => 'admin@onepazz.com.kh',
                'password'           => Hash::make('OnePazz@2026#Adm!n'),
                'phone'              => null,
                'role'               => 'platform_admin',
                'preferred_lang'     => 'en',
                'preferred_currency' => 'usd',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                array_merge($user, ['email_verified_at' => now()])
            );
        }
    }
}
