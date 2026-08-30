<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $locale = in_array($locale, ['en', 'bn'], true) ? $locale : 'en';
        $request->session()->put('locale', $locale);
        app()->setLocale($locale);

        return redirect()->intended($request->headers->get('referer') ?: route('home'));
    }
}
