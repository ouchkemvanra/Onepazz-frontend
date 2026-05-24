<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class CurrencyController extends Controller
{
    public function switch(string $currency): RedirectResponse
    {
        abort_unless(in_array($currency, ['usd', 'khr']), 404);

        session(['currency' => $currency]);

        return back();
    }
}
