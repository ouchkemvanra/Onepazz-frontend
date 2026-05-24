<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gym;
use App\Models\User;
use App\Models\Employee;
use App\Models\Checkin;
use App\Models\GymReview;

class CheckinAndReviewSeeder extends Seeder
{
    public function run(): void
    {
        $fitRepublic = Gym::where('slug', 'fit-republic-bkk')->first();
        $yoga        = Gym::where('slug', 'serenity-yoga-studio')->first();
        $boxing      = Gym::where('slug', 'khmer-boxing-academy')->first();

        $sokha  = User::where('email', 'sokha@smartretail.com.kh')->first();
        $dara   = User::where('email', 'dara@smartretail.com.kh')->first();
        $chenda = User::where('email', 'chenda@smartretail.com.kh')->first();

        $sokhaEmp  = Employee::where('user_id', $sokha->id)->first();
        $daraEmp   = Employee::where('user_id', $dara->id)->first();
        $chendaEmp = Employee::where('user_id', $chenda->id)->first();

        $checkins = [
            [$sokha,  $fitRepublic, $sokhaEmp,  now()->subDays(0)->setTime(7, 15),  75],
            [$sokha,  $yoga,        $sokhaEmp,  now()->subDays(2)->setTime(18, 0),  60],
            [$sokha,  $fitRepublic, $sokhaEmp,  now()->subDays(4)->setTime(6, 45),  75],
            [$sokha,  $fitRepublic, $sokhaEmp,  now()->subDays(6)->setTime(8, 0),   75],
            [$dara,   $fitRepublic, $daraEmp,   now()->subDays(0)->setTime(6, 0),   90],
            [$dara,   $fitRepublic, $daraEmp,   now()->subDays(1)->setTime(6, 0),   90],
            [$dara,   $boxing,      $daraEmp,   now()->subDays(2)->setTime(19, 0),  90],
            [$dara,   $fitRepublic, $daraEmp,   now()->subDays(3)->setTime(6, 0),   90],
            [$chenda, $yoga,        $chendaEmp, now()->subDays(7)->setTime(7, 0),   60],
            [$chenda, $yoga,        $chendaEmp, now()->subDays(12)->setTime(9, 0),  60],
        ];

        foreach ($checkins as [$user, $gym, $emp, $at, $dur]) {
            Checkin::create([
                'user_id'          => $user->id,
                'gym_id'           => $gym->id,
                'employee_id'      => $emp?->id,
                'checked_in_at'    => $at,
                'checked_out_at'   => $at->copy()->addMinutes($dur),
                'duration_minutes' => $dur,
            ]);
        }

        $reviews = [
            [$fitRepublic, $sokha,  5, 'Best gym in Phnom Penh. Equipment is always clean and the pool is amazing!'],
            [$fitRepublic, $dara,   5, 'ចូលចិត្តណាស់! ឧបករណ៍ល្អ ហើយគ្រូបង្រៀនជួយខ្ញុំច្រើន។'],
            [$yoga,        $chenda, 5, 'Peaceful atmosphere and excellent instructors. Highly recommend the Saturday class.'],
            [$boxing,      $sokha,  4, 'Great for authentic Muay Thai. Coaches are experienced champions.'],
        ];

        foreach ($reviews as [$gym, $user, $rating, $comment]) {
            GymReview::updateOrCreate(
                ['gym_id' => $gym->id, 'user_id' => $user->id],
                ['rating' => $rating, 'comment' => $comment]
            );
        }
    }
}
