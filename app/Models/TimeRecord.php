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
    public function calculateHoursWorked()
    {
        if ($this->clock_in_time && $this->clock_out_time) {
            // Calculer le temps total en minutes
            $totalWorked = Carbon::parse($this->clock_in_time)
                ->diffInMinutes(Carbon::parse($this->clock_out_time));

            // Soustraire la durée de la pause, si applicable
            if ($this->break_start_time && $this->break_end_time) {
                $breakDuration = Carbon::parse($this->break_start_time)
                    ->diffInMinutes(Carbon::parse($this->break_end_time));
                $totalWorked -= $breakDuration;
            }

            // Convertir le temps total en heures et minutes
            $hours = intdiv($totalWorked, 60); // Heures complètes
            $minutes = $totalWorked % 60;     // Minutes restantes

            // Stocker le temps travaillé au format "Xh Ym"
            $this->hours_worked = sprintf('%dh %02dm', $hours, $minutes);
            $this->save();
        }
    }


    

}

