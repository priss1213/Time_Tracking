<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TimeRecord;
use Carbon\Carbon;

class TimeRecordController extends Controller
{
    /**
     * Affiche la page d'accueil ou le tableau de bord.
     */
    public function index()
    {
        $employee = auth()->user();

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // $employee->load('timeRecords');

        return view('dashboard', compact('employee'));
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Assurez-vous d'avoir une vue auth/login.blade.php
    }



    /**
     * Traite les informations de connexion.
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info('Tentative de connexion avec l\'email : ' . $request->email);

        if (Auth::attempt($request->only('email', 'password'))) {
            Log::info('Utilisateur connecté : ' . Auth::user()->email);
            return redirect()->route('dashboard')->with('success', 'Connexion réussie.');
        }

        Log::warning('Échec de connexion pour l\'email : ' . $request->email);
        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }


    public function showDashboard()
    {
    // Vérifie si l'utilisateur est authentifié
    $employee = auth()->user();

    if (!$employee) {
        return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
    }

    // Charge les enregistrements de pointage de l'utilisateur
    $employee->load('timeRecords');

    // États des boutons
    $clockInDone = $timeRecord && $timeRecord->clock_in_time;
    $breakStarted = $timeRecord && $timeRecord->break_start_time;
    $breakEnded = $timeRecord && $timeRecord->break_end_time;
    $clockOutDone = $timeRecord && $timeRecord->clock_out_time;

    // Calcule les heures travaillées et ajoute les informations nécessaires pour la vue
    $timeRecords = $employee->timeRecords->map(function ($record) {
        return [
            'date' => Carbon::parse($record->created_at)->format('d/m/Y'),
            'clock_in' => $record->clock_in_time ? Carbon::parse($record->clock_in_time)->format('H:i') : '--',
            'clock_in' => $record->break_start_time ? Carbon::parse($record->break_start_time)->format('H:i') : '--',
            'clock_in' => $record->break_end_time ? Carbon::parse($record->break_end_time)->format('H:i') : '--',
            'clock_out' => $record->clock_out_time ? Carbon::parse($record->clock_out_time)->format('H:i') : '--',
            'hours_worked' => $record->hours_worked ?? '--',
        ];
    });

    // Retourne la vue avec les données nécessaires
    return view('dashboard', [
        'employee' => $employee,
        'timeRecords' => $timeRecords,
    ]);
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout()
    {
        Log::info('Déconnexion de l\'utilisateur : ' . Auth::user()->email);
        Auth::logout();
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }

    /**
     * Effectue le pointage d'entrée.
     */
    public function clockIn(Request $request)
    {
        $employee = auth()->user();

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer un pointage.');
        }

        // Vérifie si un pointage d'entrée n'a pas déjà été fait
        $lastRecord = TimeRecord::where('employee_id', $employee->id)
            ->whereNull('clock_out_time')
            ->latest()
            ->first();

        if ($lastRecord) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà pointé votre entrée.');
        }

        $timeRecord = TimeRecord::create([
            'employee_id' => $employee->id,
            'clock_in_time' => Carbon::now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Pointage d\'entrée effectué à ' . $timeRecord->clock_in_time->format('H:i'));
    }

    /**
     * Effectue le pointage de sortie.
     */
    public function clockOut(Request $request)
    {
        $employee = auth()->user();

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer un pointage.');
        }

        $timeRecord = TimeRecord::where('employee_id', $employee->id)
            ->whereNull('clock_out_time')
            ->latest()
            ->first();

        if (!$timeRecord) {
            return redirect()->route('dashboard')->with('error', 'Aucun pointage d\'entrée trouvé.');
        }

        $timeRecord->update([
            'clock_out_time' => Carbon::now(),
        ]);

        $timeRecord->calculateHoursWorked();

        return redirect()->route('dashboard')->with('success', 'Pointage de sortie effectué à ' . $timeRecord->clock_out_time->format('H:i') .
            '. Vous avez travaillé ' . $timeRecord->hours_worked . ' heure(s) aujourd\'hui.');
    }

    /**
     * Supprime le compte de l'utilisateur connecté.
     */
    public function deleteAccount()
    {
        $employee = auth()->user();

        if ($employee) {
            Log::info('Suppression du compte utilisateur : ' . $employee->email);

            $employee->timeRecords()->delete();
            $employee->delete();

            Auth::logout();
            return redirect()->route('login')->with('success', 'Votre compte a été supprimé avec succès.');
        }

        return redirect()->route('login')->with('error', 'Impossible de supprimer le compte. Veuillez réessayer.');
    }

    public function startBreak(Request $request)
{
    $employee = auth()->user();

    if (!$employee) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour enregistrer une pause.');
    }

    $timeRecord = TimeRecord::where('employee_id', $employee->id)
        ->whereNull('clock_out_time')
        ->latest()
        ->first();

    if (!$timeRecord) {
        return redirect()->route('dashboard')->with('error', 'Aucun pointage d\'entrée trouvé.');
    }

    if ($timeRecord->break_start_time) {
        return redirect()->route('dashboard')->with('error', 'La pause a déjà commencé.');
    }

    $timeRecord->update([
        'break_start_time' => Carbon::now(),
    ]);

    return redirect()->route('dashboard')->with('success', 'Début de pause enregistré.');
}

public function endBreak(Request $request)
{
    $employee = auth()->user();

    if (!$employee) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour enregistrer une pause.');
    }

    $timeRecord = TimeRecord::where('employee_id', $employee->id)
        ->whereNull('clock_out_time')
        ->latest()
        ->first();

    if (!$timeRecord) {
        return redirect()->route('dashboard')->with('error', 'Aucun pointage d\'entrée trouvé.');
    }

    if (!$timeRecord->break_start_time) {
        return redirect()->route('dashboard')->with('error', 'Vous devez d\'abord enregistrer le début de la pause.');
    }

    if ($timeRecord->break_end_time) {
        return redirect()->route('dashboard')->with('error', 'La pause a déjà été terminée.');
    }

    $timeRecord->update([
        'break_end_time' => Carbon::now(),
    ]);

    return redirect()->route('dashboard')->with('success', 'Fin de pause enregistrée.');
}


}
