<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale');

        if (! $locale && $request->route() && $request->route()->parameter('locale')) {
            $locale = $request->route()->parameter('locale');
        }

        $locale = in_array($locale, ['en', 'bn'], true) ? $locale : (app()->getLocale() ?: 'en');
        $request->session()->put('locale', $locale);
        app()->setLocale($locale);

        return $next($request);
    }
}
