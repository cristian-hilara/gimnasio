<?php

namespace Database\Seeders;

use App\Models\Objetivo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObjetivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Objetivo::create(['nombre' => 'Bajar de peso', 'descripcion' => 'Rutinas con cardio y fuerza ligera']);
        Objetivo::create(['nombre' => 'Tonificar', 'descripcion' => 'Rutinas de resistencia y definición']);
        Objetivo::create(['nombre' => 'Ganar masa', 'descripcion' => 'Rutinas con cargas progresivas y menos repeticiones']);
        Objetivo::create(['nombre' => 'Mejorar resistencia cardiovascular', 'descripcion' => 'Entrenamientos de cardio, HIIT y ciclismo']);
        Objetivo::create(['nombre' => 'Aumentar fuerza', 'descripcion' => 'Rutinas de levantamiento de pesas y powerlifting']);
        Objetivo::create(['nombre' => 'Flexibilidad y movilidad', 'descripcion' => 'Yoga, pilates y estiramientos dinámicos']);
        Objetivo::create(['nombre' => 'Rehabilitación', 'descripcion' => 'Ejercicios suaves y de bajo impacto para recuperación']);
        Objetivo::create(['nombre' => 'Bienestar general', 'descripcion' => 'Rutinas mixtas para mantenerse activo y saludable']);
        Objetivo::create(['nombre' => 'Reducir estrés', 'descripcion' => 'Yoga, meditación y rutinas relajantes']);
        Objetivo::create(['nombre' => 'Preparación para competencia', 'descripcion' => 'Entrenamientos avanzados y periodización']);
        Objetivo::create(['nombre' => 'Mejorar postura', 'descripcion' => 'Ejercicios de espalda y core']);
        Objetivo::create(['nombre' => 'Definición muscular', 'descripcion' => 'Rutinas con más repeticiones y menos carga']);
    }
}
