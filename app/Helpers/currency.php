<?php

use Illuminate\Support\Facades\View;

if (!function_exists('format_currency')) {
    /**
     * Format a USD amount in the user's selected currency.
     */
    function format_currency(float $usd, int $usdDecimals = 0): string
    {
        $currency = View::shared('currency', 'usd');
        $khrRate  = (float) View::shared('khrRate', 4100);

        if ($currency === 'khr') {
            return number_format((int) round($usd * $khrRate)) . ' ៛';
        }

        return '$' . number_format($usd, $usdDecimals);
    }
}
