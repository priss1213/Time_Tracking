<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyHoursWorkedColumnInTimeRecordsTable extends Migration
{
    public function up()
    {
        Schema::table('time_records', function (Blueprint $table) {
            $table->string('hours_worked')->nullable()->change(); // Change le type en string
        });
    }

    public function down()
    {
        Schema::table('time_records', function (Blueprint $table) {
            $table->string('hours_worked')->nullable()->change(); // Revenir au type original si nécessaire
        });
    }
}
