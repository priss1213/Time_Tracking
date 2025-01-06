@extends('layouts.app')


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
            <button type="submit"  id="clock-in-btn" class="btn btn-success btn-lg" >📥 Pointage Entrée</button>
        </form>
        <form action="{{ route('break.start') }}" method="POST" style="display: inline-block;">
            @csrf
            <button type="submit" id="start-break-btn" class="btn btn-warning" >Commencer la pause</button>
        </form>

        <form action="{{ route('break.end') }}" method="POST" style="display: inline-block;">
            @csrf
            <button type="submit" id="end-break-btn" class="btn btn-success" >Terminer la pause</button>
        </form>
        <form action="{{ route('clock.out') }}" method="POST" style="display: inline-block; margin-left: 10px;">
            @csrf
            <button type="submit" id="clock-out-btn" class="btn btn-warning btn-lg" >📤 Pointage Sortie</button>
        </form>



    </div>

    <script>
        ddocument.addEventListener('DOMContentLoaded', function () {
    const clockInBtn = document.getElementById('clock-in-btn');
    const startBreakBtn = document.getElementById('start-break-btn');
    const endBreakBtn = document.getElementById('end-break-btn');
    const clockOutBtn = document.getElementById('clock-out-btn');

    // Simulez des états pour vérifier le comportement
    clockInBtn.disabled = true;
    startBreakBtn.disabled = true;
    endBreakBtn.disabled = true;
    clockOutBtn.disabled = true;
});


    function updateButtonStates() {
        fetch('/api/current-status')
            .then(response => response.json())
            .then(status => {
                console.log('Statut actuel:', status); // Vérifie les données récupérées
                clockInBtn.disabled = !status.canClockIn;
                startBreakBtn.disabled = !status.canStartBreak;
                endBreakBtn.disabled = !status.canEndBreak;
                clockOutBtn.disabled = !status.canClockOut;
                console.log('Boutons mis à jour.');
            })
            .catch(error => console.error('Erreur lors de la récupération du statut:', error));
    }

    // Charge l'état des boutons au chargement de la page
    updateButtonStates();
});


            // Ajouter des événements aux boutons
            clockInBtn.addEventListener('click', () => {
                fetch('/api/clock-in', { method: 'POST' })
                    .then(() => updateButtonStates())
                    .catch(error => console.error('Erreur lors du pointage:', error));
            });

            startBreakBtn.addEventListener('click', () => {
                fetch('/api/start-break', { method: 'POST' })
                    .then(() => updateButtonStates())
                    .catch(error => console.error('Erreur lors du début de pause:', error));
            });

            endBreakBtn.addEventListener('click', () => {
                fetch('/api/end-break', { method: 'POST' })
                    .then(() => updateButtonStates())
                    .catch(error => console.error('Erreur lors de la fin de pause:', error));
            });

            clockOutBtn.addEventListener('click', () => {
                fetch('/api/clock-out', { method: 'POST' })
                    .then(() => updateButtonStates())
                    .catch(error => console.error('Erreur lors de la sortie:', error));
            });
        });
    </script>







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






            </tbody>
        </table>
    </div>
</div>



@endsection
