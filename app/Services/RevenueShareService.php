<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\Gym;
use App\Models\Invoice;
use App\Models\PlatformConfig;
use Illuminate\Support\Collection;

class RevenueShareService
{
    public function calculatePartnerPayouts(int $year, int $month): Collection
    {
        $khrRate        = (float) PlatformConfig::get('khr_rate', 4100);
        $defaultSharePct = (float) PlatformConfig::get('revenue_share_pct_default', 30);

        $checkinsPerUnit = [
            'gold'   => (int) PlatformConfig::get('checkins_per_unit_gold', 15),
            'silver' => (int) PlatformConfig::get('checkins_per_unit_silver', 20),
            'bronze' => (int) PlatformConfig::get('checkins_per_unit_bronze', 25),
        ];

        $totalRevenue = Invoice::where('status', 'paid')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount_usd');

        $gyms = Gym::where('status', 'active')->get();

        $partnerData = [];
        $totalUnits  = 0;

        foreach ($gyms as $gym) {
            $checkins = Checkin::where('gym_id', $gym->id)
                ->whereYear('checked_in_at', $year)
                ->whereMonth('checked_in_at', $month)
                ->count();

            $perUnit = $checkinsPerUnit[$gym->tier] ?? 25;
            $units   = $perUnit > 0 ? floor($checkins / $perUnit) : 0;
            $totalUnits += $units;

            $partnerData[] = compact('gym', 'checkins', 'units');
        }

        $valuePerUnit = $totalUnits > 0 ? ($totalRevenue / $totalUnits) : 0;

        return collect($partnerData)->map(function ($data) use ($valuePerUnit, $defaultSharePct, $khrRate) {
            $gym       = $data['gym'];
            $sharePct  = $gym->revenue_share_pct ?? $defaultSharePct;
            $gross     = $data['units'] * $valuePerUnit;
            $payoutUsd = round($gross * (1 - $sharePct / 100), 2);
            $cut       = round($gross * ($sharePct / 100), 2);

            return [
                'gym_id'           => $gym->id,
                'gym_name'         => $gym->name,
                'tier'             => $gym->tier,
                'checkins'         => $data['checkins'],
                'units'            => $data['units'],
                'value_per_unit'   => round($valuePerUnit, 4),
                'payout_usd'       => $payoutUsd,
                'khmerfit_cut_usd' => $cut,
                'payout_khr'       => round($payoutUsd * $khrRate),
                'revenue_share_pct' => $sharePct,
            ];
        });
    }
}
