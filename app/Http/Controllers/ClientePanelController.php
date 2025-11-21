<?php

namespace App\Http\Controllers;

use App\Models\ActividadHorario;
use Auth;
use Illuminate\Http\Request;

class ClientePanelController extends Controller
{
   public function index()
    {
        $cliente = Auth::user()->cliente;

        if (!$cliente) {
            return redirect()->route('errors.cliente_no_registrado')
                ->with('warning', 'Tu perfil de cliente aún no está registrado.');
        }

        // Actividades disponibles
        $actividades = ActividadHorario::with(['actividad', 'instructor.usuario', 'sala'])
            ->where('estado', true)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        // Membresía actual
        $membresia = $cliente->historialMembresias()->latest()->first();

        // Rutinas
        $rutinaActiva = $cliente->rutinas()->where('estado', 'activa')->with('ejercicios')->first();
        $rutinasAnteriores = $cliente->rutinas()->where('estado', 'inactiva')->get();

        return view('panel.panel_cliente', compact('cliente', 'actividades', 'membresia', 'rutinaActiva', 'rutinasAnteriores'));
    }
}
