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
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'client':
                    return redirect()->route('frontOffice.accueil');
                case 'gestionnaire':
                    return redirect()->route('gestion.catalogue');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'role' => 'Rôle non reconnu.',
                    ]);
            }
        }

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
                return redirect()->route('frontOffice.accueil');
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
