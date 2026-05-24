<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gym;
use App\Models\GymClass;

class GymSeeder extends Seeder
{
    public function run(): void
    {
        $gyms = [
            [
                'name'            => 'Fit Republic BKK',
                'name_kh'         => 'ហ្វីតរីពាប្លីក BKK',
                'slug'            => 'fit-republic-bkk',
                'description'     => "Phnom Penh's premier fitness destination. 2,500 sqm of world-class equipment, an Olympic-size pool, and 40+ weekly group classes.",
                'address_line1'   => 'Street 51, BKK1',
                'district'        => 'Chamkarmon',
                'city'            => 'Phnom Penh',
                'province'        => 'Phnom Penh',
                'latitude'        => 11.5293,
                'longitude'       => 104.9219,
                'phone'           => '+85523987654',
                'email'           => 'info@fitrepublic.com.kh',
                'activity_types'  => json_encode(['weights', 'cardio', 'pool', 'classes', 'crossfit']),
                'amenities'       => json_encode(['sauna', 'steam', 'locker', 'parking', 'towel', 'wifi']),
                'tier'            => 'gold',
                'operating_hours' => json_encode([
                    'mon' => ['open' => '05:30', 'close' => '23:00'],
                    'tue' => ['open' => '05:30', 'close' => '23:00'],
                    'wed' => ['open' => '05:30', 'close' => '23:00'],
                    'thu' => ['open' => '05:30', 'close' => '23:00'],
                    'fri' => ['open' => '05:30', 'close' => '23:00'],
                    'sat' => ['open' => '06:00', 'close' => '22:00'],
                    'sun' => ['open' => '07:00', 'close' => '20:00'],
                ]),
                'status'         => 'active',
                'partner_since'  => '2024-01-15',
                'average_rating' => 4.80,
                'review_count'   => 142,
            ],
            [
                'name'            => 'Serenity Yoga Studio',
                'name_kh'         => 'ស្ទូឌីយ៉ូយូហ្គា Serenity',
                'slug'            => 'serenity-yoga-studio',
                'description'     => "Phnom Penh's most-loved yoga and pilates studio. Small class sizes, certified instructors.",
                'address_line1'   => 'Street 350, Tonle Bassac',
                'district'        => 'Chamkarmon',
                'city'            => 'Phnom Penh',
                'province'        => 'Phnom Penh',
                'latitude'        => 11.5168,
                'longitude'       => 104.9275,
                'phone'           => '+85523876543',
                'email'           => 'hello@serenityyoga.kh',
                'activity_types'  => json_encode(['yoga', 'pilates', 'meditation']),
                'amenities'       => json_encode(['locker', 'mat_rental', 'wifi']),
                'tier'            => 'silver',
                'operating_hours' => json_encode([
                    'mon' => ['open' => '06:00', 'close' => '20:00'],
                    'tue' => ['open' => '06:00', 'close' => '20:00'],
                    'wed' => ['open' => '06:00', 'close' => '20:00'],
                    'thu' => ['open' => '06:00', 'close' => '20:00'],
                    'fri' => ['open' => '06:00', 'close' => '20:00'],
                    'sat' => ['open' => '07:00', 'close' => '18:00'],
                    'sun' => ['open' => '07:00', 'close' => '16:00'],
                ]),
                'status'         => 'active',
                'partner_since'  => '2024-03-01',
                'average_rating' => 4.90,
                'review_count'   => 87,
            ],
            [
                'name'            => 'Khmer Boxing Academy',
                'name_kh'         => 'មហាវិទ្យាល័យប្រដាល់ខ្មែរ',
                'slug'            => 'khmer-boxing-academy',
                'description'     => 'Authentic Bokator and Muay Thai training in the heart of Daun Penh.',
                'address_line1'   => 'Street 106, Daun Penh',
                'district'        => 'Daun Penh',
                'city'            => 'Phnom Penh',
                'province'        => 'Phnom Penh',
                'latitude'        => 11.5674,
                'longitude'       => 104.9231,
                'phone'           => '+85512345678',
                'activity_types'  => json_encode(['muay_thai', 'boxing', 'mma', 'bokator']),
                'amenities'       => json_encode(['locker', 'ring', 'heavy_bags']),
                'tier'            => 'bronze',
                'operating_hours' => json_encode([
                    'mon' => ['open' => '07:00', 'close' => '21:00'],
                    'tue' => ['open' => '07:00', 'close' => '21:00'],
                    'wed' => ['open' => '07:00', 'close' => '21:00'],
                    'thu' => ['open' => '07:00', 'close' => '21:00'],
                    'fri' => ['open' => '07:00', 'close' => '21:00'],
                    'sat' => ['open' => '08:00', 'close' => '18:00'],
                    'sun' => ['closed' => true],
                ]),
                'status'         => 'active',
                'partner_since'  => '2024-06-10',
                'average_rating' => 4.50,
                'review_count'   => 61,
            ],
        ];

        foreach ($gyms as $gym) {
            Gym::updateOrCreate(['slug' => $gym['slug']], $gym);
        }

        // Seed classes for Fit Republic
        $fitRepublic = Gym::where('slug', 'fit-republic-bkk')->first();
        if ($fitRepublic) {
            $classes = [
                ['name' => 'Morning Yoga Flow',      'trainer_name' => 'Ratana S.',  'class_type' => 'yoga',     'day_of_week' => json_encode([1,3,5]),     'start_time' => '06:00', 'duration_minutes' => 60, 'max_capacity' => 15],
                ['name' => 'HIIT Circuit Training',  'trainer_name' => 'Dara M.',    'class_type' => 'hiit',     'day_of_week' => json_encode([2,4]),        'start_time' => '08:30', 'duration_minutes' => 45, 'max_capacity' => 20],
                ['name' => 'Power Pump',              'trainer_name' => 'Chenda R.',  'class_type' => 'weights',  'day_of_week' => json_encode([1,2,3,4,5]), 'start_time' => '19:30', 'duration_minutes' => 60, 'max_capacity' => 25],
                ['name' => 'Muay Thai Fundamentals', 'trainer_name' => 'Sopheak K.', 'class_type' => 'muay_thai','day_of_week' => json_encode([1,3,5]),     'start_time' => '18:00', 'duration_minutes' => 75, 'max_capacity' => 12],
            ];
            foreach ($classes as $class) {
                GymClass::updateOrCreate(
                    ['gym_id' => $fitRepublic->id, 'name' => $class['name']],
                    array_merge($class, ['gym_id' => $fitRepublic->id, 'is_active' => true])
                );
            }
        }
    }
}
