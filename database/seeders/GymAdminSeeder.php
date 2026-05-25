<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GymAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'gymadmin@fitrepublic.com.kh'],
            [
                'full_name'  => 'Fit Republic Admin',
                'password'   => Hash::make('password123'),
                'role'       => 'gym_admin',
                'is_active'  => true,
            ]
        );

        $gym = Gym::where('name', 'Fit Republic BKK')->first();

        if ($gym) {
            $gym->update(['admin_user_id' => $user->id]);
            $this->command->info("Linked {$user->email} to existing gym: {$gym->name}");
        } else {
            Gym::create([
                'name'          => 'Fit Republic BKK',
                'name_kh'       => 'ហ្វីត រីប៊ូប្លិក BKK',
                'slug'          => 'fit-republic-bkk',
                'description'   => 'Premium fitness centre in BKK1, Phnom Penh.',
                'city'          => 'Phnom Penh',
                'district'      => 'Boeung Keng Kang',
                'admin_user_id' => $user->id,
                'status'        => 'active',
                'tier'          => 'gold',
                'partner_since' => now(),
            ]);
            $this->command->info("Created Fit Republic BKK and linked to {$user->email}");
        }
    }
}
