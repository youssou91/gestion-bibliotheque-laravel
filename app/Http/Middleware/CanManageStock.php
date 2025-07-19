<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanManageStock
{
    /**
     * Handle an incoming request.
     * Permet l'accès aux administrateurs ET aux gestionnaires pour la gestion de stock.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'gestionnaire')) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Accès réservé aux administrateurs et gestionnaires pour la gestion de stock.');
    }
}
