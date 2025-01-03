<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Affiche le formulaire d'enregistrement d'un employé.
     */
    public function create()
    {
        return view('auth.register'); // Vue avec le formulaire
    }

    /**
     * Enregistre un nouvel employé dans la base de données.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $employee = Employee::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('employees.create')->with('success', 'Employé enregistré avec succès.');
    }
}
