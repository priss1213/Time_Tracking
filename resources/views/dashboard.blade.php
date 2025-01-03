@extends('layouts.app')

{{-- @php
    $clockInDone = $clockInDone ?? false;
    $breakStarted = $breakStarted ?? true;
    $breakEnded = $breakEnded ?? false;
    $clockOutDone = $clockOutDone ?? false;
@endphp --}}


@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Bonjour, {{ $employee->name }}</h1>

        {{-- Bouton de retour --}}
       <a href="{{ route('login') }}" class="btn btn-primary">Accueil</a>
    </div>

    <!-- Boutons de pointage -->
    <div class="mb-4">
        <form action="{{ route('clock.in') }}" method="POST" style="display: inline-block;">
            @csrf
            <button type="submit"   class="btn btn-success btn-lg" >📥 Pointage Entrée</button>
        </form>
        <form action="{{ route('break.start') }}" method="POST" style="display: inline-block;">
            @csrf
            <button type="submit"  class="btn btn-warning" >Commencer la pause</button>
        </form>

        <form action="{{ route('break.end') }}" method="POST" style="display: inline-block;">
            @csrf
            <button type="submit"  class="btn btn-success" >Terminer la pause</button>
        </form>
        <form action="{{ route('clock.out') }}" method="POST" style="display: inline-block; margin-left: 10px;">
            @csrf
            <button type="submit" class="btn btn-warning btn-lg" >📤 Pointage Sortie</button>
        </form>



    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnClockIn = document.getElementById('btn-clock-in');
            const btnStartBreak = document.getElementById('btn-start-break');
            const btnEndBreak = document.getElementById('btn-end-break');
            const btnClockOut = document.getElementById('btn-clock-out');

            $timeRecord = 'some value';  // Initialisez la variable avant de l'utiliser


                @if(!$timeRecord->clock_in_time)
                    btnClockIn.disabled = false;
                    btnStartBreak.disabled = true;
                    btnEndBreak.disabled = true;
                    btnClockOut.disabled = true;
                @elseif(!$timeRecord->break_start_time)
                    btnClockIn.disabled = true;
                    btnStartBreak.disabled = false;
                    btnEndBreak.disabled = true;
                    btnClockOut.disabled = true;
                @elseif(!$timeRecord->break_end_time)
                    btnClockIn.disabled = true;
                    btnStartBreak.disabled = true;
                    btnEndBreak.disabled = false;
                    btnClockOut.disabled = true;
                @else
                    btnClockIn.disabled = true;
                    btnStartBreak.disabled = true;
                    btnEndBreak.disabled = true;
                    btnClockOut.disabled = false;
                @endif
            @endif
        });
    </script> --}}



    <!-- Section des messages -->
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Historique des pointages -->
    <h2 class="text-secondary mt-4">Historique des Pointages</h2>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Heure d'Entrée</th>
                    <th>Début de la pause</th>
                    <th>Fin de la pause</th>
                    <th>Heure de Sortie</th>
                    <th>Heures Travaillées</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employee->timeRecords as $record)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}</td>
                        <td>{{ $record->clock_in_time ? \Carbon\Carbon::parse($record->clock_in_time)->format('H:i') : '--' }}</td>
                        <td>{{ $record->break_start_time ? \Carbon\Carbon::parse($record->break_start_time)->format('H:i') : '--' }}</td>
                        <td>{{ $record->break_end_time ? \Carbon\Carbon::parse($record->break_end_time)->format('H:i') : '--' }}</td>
                        <td>{{ $record->clock_out_time ? \Carbon\Carbon::parse($record->clock_out_time)->format('H:i') : '--' }}</td>
                        <td>{{ $record->hours_worked ?? '--' }}</td>
                    </tr>
                @endforeach
                @if($employee->timeRecords->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aucun enregistrement trouvé.</td>
                    </tr>
                @endif
{{--
                        <script>
                                document.addEventListener('DOMContentLoaded', () => {
                        // Sélectionner les boutons
                        const btnClockIn = document.getElementById('btn-clock-in');
                        const btnStartBreak = document.getElementById('btn-start-break');
                        const btnEndBreak = document.getElementById('btn-end-break');
                        const btnClockOut = document.getElementById('btn-clock-out');

                        // Désactiver les boutons au chargement initial
                        btnStartBreak.disabled = true;
                        btnEndBreak.disabled = true;
                        btnClockOut.disabled = true;

                        // Ajouter les gestionnaires d'événements
                        btnClockIn.addEventListener('click', (e) => {
                            e.preventDefault(); // Empêche la soumission immédiate du formulaire
                            btnClockIn.disabled = true; // Désactiver "Pointage Entrée"
                            btnStartBreak.disabled = false; // Activer "Commencer la pause"

                            // Simuler une soumission après le traitement
                            e.target.form.submit();
                        });

                        btnStartBreak.addEventListener('click', (e) => {
                            e.preventDefault();
                            btnStartBreak.disabled = true; // Désactiver "Commencer la pause"
                            btnEndBreak.disabled = false; // Activer "Terminer la pause"
                            e.target.form.submit();
                        });

                        btnEndBreak.addEventListener('click', (e) => {
                            e.preventDefault();
                            btnEndBreak.disabled = true; // Désactiver "Terminer la pause"
                            btnClockOut.disabled = false; // Activer "Pointage Sortie"
                            e.target.form.submit();
                        });

                        btnClockOut.addEventListener('click', (e) => {
                            e.preventDefault();
                            btnClockOut.disabled = true; // Désactiver "Pointage Sortie"
                            alert('Votre journée est terminée.');
                            e.target.form.submit();
                        });
                    });
                    </script> --}}




            </tbody>
        </table>
    </div>
</div>



@endsection
