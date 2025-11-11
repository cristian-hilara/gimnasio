<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class ClientePanelController extends Controller
{
    public function index()
    {
        $cliente = Auth::user()->cliente;

        // Todas las actividades disponibles (no filtradas por cliente)
        $actividades = \App\Models\ActividadHorario::with(['actividad', 'instructor.usuario', 'sala'])
            ->where('estado', true) // solo actividades activas
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        // Membresía actual
        $membresia = $cliente->historialMembresias()->latest()->first();

        // Rutinas
        $rutinaActiva = $cliente->rutinas()->where('estado', 'activa')->with('ejercicios')->first();
        $rutinasAnteriores = $cliente->rutinas()->where('estado', 'inactiva')->get();

        return view('panel.panel_cliente', compact('actividades', 'membresia', 'rutinaActiva', 'rutinasAnteriores'));
    }
}
