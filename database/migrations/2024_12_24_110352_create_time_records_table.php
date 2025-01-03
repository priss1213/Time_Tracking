<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('time_records', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->unsignedBigInteger('employee_id'); // Relation avec l'employé
            $table->timestamp('clock_in_time')->nullable(); // Heure d'entrée
            $table->timestamp('clock_out_time')->nullable(); // Heure de sortie
            $table->integer('hours_worked')->nullable(); // Heures travaillées
            $table->timestamps(); // created_at et updated_at

            // Clé étrangère
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('time_records');
    }
}
