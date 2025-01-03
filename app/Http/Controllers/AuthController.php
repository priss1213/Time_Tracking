<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validation des données
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Logique pour authentifier l'utilisateur
        // Exemple : Auth::attempt(['email' => $request->email, 'password' => $request->password])

        return redirect()->back()->with('success', 'Connexion réussie !');
    }
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Assurez-vous d'avoir une vue auth/login.blade.php
    }

    /**
     * Traite le formulaire de connexion.
     */
    public function processLogin(Request $request)
    {
        // Valide les données du formulaire
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Tentative de connexion
        if (Auth::attempt($request->only('email', 'password'))) {
            // Redirige vers le tableau de bord après une connexion réussie
            return redirect()->route('dashboard')->with('success', 'Connexion réussie.');
        }

        // Redirige en cas d'échec de connexion
        return back()->withErrors([
            'email' => 'Les identifiants sont incorrects.',
        ])->withInput();
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout()
    {
        Auth::logout();

        // Redirige vers la page de connexion après déconnexion
        return redirect()->route('login')->with('success', 'Vous avez été déconnecté.');
    }
}
