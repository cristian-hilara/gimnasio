<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actividad_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades');
            $table->string('dia_semana'); // Ej: 'lunes', 'martes', etc.
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('cupo_maximo');
            $table->boolean('estado')->default(true);

            $table->foreignId('instructor_id')->constrained('instructors');
            $table->foreignId('sala_id')->constrained('salas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_horarios');
    }
};
