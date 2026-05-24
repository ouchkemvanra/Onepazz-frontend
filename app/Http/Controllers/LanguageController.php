<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, ['en', 'km']), 404);

        session(['locale' => $locale]);

        if ($user = auth()->user()) {
            $user->update(['preferred_lang' => $locale === 'km' ? 'kh' : 'en']);
        }

        return back();
    }
}
