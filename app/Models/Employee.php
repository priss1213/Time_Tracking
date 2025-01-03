<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\TimeRecord;

class Employee extends Authenticatable
{   use HasFactory;
    use Notifiable;


    protected $fillable = ['name', 'email', 'password'];

    // Ajoutez cette propriété si nécessaire pour préciser que l'email est utilisé pour l'authentification
    protected $hidden = [  'password',
    'remember_token',
];

  // Définir la relation hasMany avec TimeRecord
    public function timeRecords()
    {
        return $this->hasMany(TimeRecord::class, 'employee_id');
    }





    // Méthode pour obtenir les pointages d'un employé pour une période donnée
    public function getTimeRecordsForDateRange($startDate, $endDate)
    {
        return $this->timeRecords()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
    }
}

