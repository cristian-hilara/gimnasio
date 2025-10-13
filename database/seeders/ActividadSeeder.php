<?php

namespace Database\Seeders;

use App\Models\Actividad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Actividad::create([
            'nombre' => 'Salsa Principiantes',
            'tipo_actividad' => 'baile',
            'dias' => ['lunes', 'miercoles', 'viernes'], 
            'hora_inicio' => '19:00:00', 
            'hora_fin' => '21:00:00',
            
            'cupo_maximo' => 20, // Sin comillas (es integer)
            'estado' => true, // O 1, ambos funcionan
            'instructor_id' => 1, 
            'sala_id' => 1, 
        ]);
    }
}
