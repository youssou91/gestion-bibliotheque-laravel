<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected function redirectTo()
    // {
    //     $role = auth()->user()->role;

    //     if ($role === 'admin') {
    //         return route('admin.dashboard'); 
    //     } elseif ($role === 'client') {
    //         return route('frontOffice.accueil');
    //     }

    //     return '/'; // redirection par défaut
    // }
    protected function authenticated(Request $request, $user)
    {
        if ($user->statut !== 'actif') {
            Auth::logout();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre compte est ' . $user->statut . '. Veuillez contacter l\'administrateur.'
                ], 403);
            }
            
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte est ' . $user->statut . '. Veuillez contacter l\'administrateur.',
            ]);
        }

        // Si c'est une requête API, retourner un token
        if ($request->wantsJson()) {
            $token = $user->createToken('api-token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user->only(['id', 'name', 'email', 'role']),
                'redirect' => $user->role === 'admin' 
                    ? route('admin.dashboard')
                    : route('frontOffice.accueil')
            ]);
        }

        // Redirection selon le rôle pour les requêtes web
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'client') {
            return redirect()->route('frontOffice.accueil');
        }

        return redirect('/');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
