<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;


class TimeRecord extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'clock_in_time',  'break_start_time', 'break_end_time', 'clock_out_time',
    'hours_worked'];

    // Relation avec l'employé
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Méthode pour saisir l'heure d'arrivée
    public function setclock_in_time()
    {
        $this->clock_in_time = now();
        $this->save();
    }

    // Méthode pour saisir l'heure de départ
    public function setclock_out_time()
    {
        $this->clock_out_time = now();
        $this->save();
    }

    // Calcul des heures travaillées
    // public function calculateHoursWorked()
    // {
    //     if ($this->clock_in_time && $this->clock_out_time) {
    //         // Calculer le temps total en minutes
    //         $totalWorked = Carbon::parse($this->clock_in_time)
    //             ->diffInMinutes(Carbon::parse($this->clock_out_time));

    //         // Soustraire la durée de la pause, si applicable
    //         if ($this->break_start_time && $this->break_end_time) {
    //             $breakDuration = Carbon::parse($this->break_start_time)
    //                 ->diffInMinutes(Carbon::parse($this->break_end_time));
    //             $totalWorked -= $breakDuration;
    //         }

    //         // Convertir le temps total en heures et minutes
    //         $hours = intdiv($totalWorked, 60); // Heures complètes
    //         $minutes = $totalWorked % 60;     // Minutes restantes

    //         // Stocker le temps travaillé au format "Xh Ym"
    //         $this->hours_worked = sprintf('%dh %02dm', $hours, $minutes);
    //         $this->save();
    //     }
    // }

    public function calculateHoursWorked()
    {
        if ($this->clock_in_time && $this->clock_out_time) {
            $clockIn = Carbon::parse($this->clock_in_time);
            $clockOut = Carbon::parse($this->clock_out_time);

            $totalWorked = $clockOut->diffInMinutes($clockIn);

            if ($this->break_start_time && $this->break_end_time) {
                $breakStart = Carbon::parse($this->break_start_time);
                $breakEnd = Carbon::parse($this->break_end_time);

                // Soustraire les minutes de pause
                $breakDuration = $breakEnd->diffInMinutes($breakStart);
                $totalWorked -= $breakDuration;
            }

             // Convertir le temps total en heures et minutes
            $hours = intdiv($totalWorked, 60); // Heures complètes
            $minutes = $totalWorked % 60;     // Minutes restantes
            // Stocker le temps travaillé au format "XH YM"

            $this->hours_worked = sprintf('%dh %02dm', $hours, $minutes);
            $this->save();
        }
    }

    // TimeRecordController.php

public function getCurrentStatus()
{
    $employee = auth()->user();

    if (!$employee) {
        return response()->json(['error' => 'Utilisateur non authentifié'], 403);
    }

    $today = Carbon::today();
    $timeRecord = TimeRecord::where('employee_id', $employee->id)
        ->whereDate('clock_in_time', $today)
        ->latest()
        ->first();

    $status = [
        'canClockIn' => !$timeRecord, // Si aucun pointage aujourd'hui, on peut pointer à l'entrée
        'canStartBreak' => $timeRecord && !$timeRecord->break_start_time && !$timeRecord->clock_out_time,
        'canEndBreak' => $timeRecord && $timeRecord->break_start_time && !$timeRecord->break_end_time,
        'canClockOut' => $timeRecord && $timeRecord->break_end_time && !$timeRecord->clock_out_time,
    ];

    return response()->json($status);
}





}

