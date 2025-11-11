<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EjercicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $ejercicios = [
            ['nombre' => 'Sentadillas', 'grupo_muscular' => 'Piernas', 'descripcion' => 'Ejercicio básico para cuádriceps y glúteos'],
            ['nombre' => 'Press banca', 'grupo_muscular' => 'Pecho', 'descripcion' => 'Ejercicio compuesto para pectorales'],
            ['nombre' => 'Dominadas', 'grupo_muscular' => 'Espalda', 'descripcion' => 'Ejercicio para dorsales y bíceps'],
            ['nombre' => 'Curl bíceps', 'grupo_muscular' => 'Brazos', 'descripcion' => 'Ejercicio de aislamiento para bíceps'],
            ['nombre' => 'Plancha abdominal', 'grupo_muscular' => 'Abdomen', 'descripcion' => 'Ejercicio isométrico para core'],
            ['nombre' => 'Hip thrust', 'grupo_muscular' => 'Glúteos', 'descripcion' => 'Ejercicio clave para glúteos'],
        ];

        foreach ($ejercicios as $ejercicio) {
            Ejercicio::create($ejercicio);
        }
    }
}
