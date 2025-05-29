<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLoginForm', 'login']);
    }

    public function showLoginForm()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        // Étape 1 : Validation des champs
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Étape 2 : Récupérer les identifiants
        $credentials = $request->only('email', 'password');

        // Étape 3 : Tentative d'authentification
        if (Auth::attempt($credentials)) {
            // Étape 4 : Regénérer la session pour sécurité
            $request->session()->regenerate();

            $user = Auth::user();

            // Étape 5 : Redirection selon le rôle
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'client':
                    return redirect()->route('frontOffice.home');
                case 'gestionnaire':
                    return redirect()->route('gestionnaire.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'role' => 'Rôle inconnu.',
                    ]);
            }
        }

        // Étape 6 : En cas d'échec
        return back()->withErrors([
            'email' => 'Identifiants invalides',
        ]);
    }


    public function dashboard()
    {
        $user = Auth::user();
        return $this->redirectByRole($user->role);
    }

    protected function redirectByRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'gestionnaire':
                return redirect()->route('gestion.catalogue');
            case 'client':
                return redirect()->route('frontOffice.home');
            default:
                return redirect('/'); // Sécurité si le rôle n’est pas reconnu
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
