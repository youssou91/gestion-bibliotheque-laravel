<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsGestionnaire
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->role === 'gestionnaire' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Accès réservé aux gestionnaires et administrateurs.');
    }
}
