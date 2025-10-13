<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\ActividadHorario;
use App\Models\Instructor;
use App\Models\Sala;
use Illuminate\Http\Request;

class TipoActividadHorarioController extends Controller
{
    public function index()
    {
        $horarios = ActividadHorario::with(['actividad.tipoActividad', 'instructor.usuario', 'sala'])->get();
        return view('actividades.horarios.index', compact('horarios'));
    }

    public function create()
    {
        $actividades = Actividad::with('tipoActividad')->get();
        $instructores = Instructor::with('usuario')->where('estado', 'activo')->get();
        $salas = Sala::where('estado', 'disponible')->get();
        
        return view('actividades.horarios.create', compact('actividades', 'instructores', 'salas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'actividad_id' => 'required|exists:actividades,id',
            'dia_semana' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'cupo_maximo' => 'required|integer|min:1',
            'instructor_id' => 'required|exists:instructors,id',
            'sala_id' => 'required|exists:salas,id',
            'estado' => 'required|boolean'
        ], [
            'actividad_id.required' => 'Debe seleccionar una actividad',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio',
            'cupo_maximo.min' => 'El cupo debe ser al menos 1'
        ]);

        try {
            ActividadHorario::create($request->all());
            return redirect()->route('actividad_horarios.index')
                ->with('success', 'Horario de actividad creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el horario: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(ActividadHorario $horario)
    {
        $horario->load(['actividad.tipoActividad', 'instructor.usuario', 'sala']);
        return response()->json($horario);
    }

    public function edit(ActividadHorario $actividad_horario)
    {
        $actividades = Actividad::with('tipoActividad')->get();
        $instructores = Instructor::with('usuario')->where('estado', 'activo')->get();
        $salas = Sala::where('estado', 'activo')->get();
        
        return view('actividades.horarios.edit', compact('actividad_horario', 'actividades', 'instructores', 'salas'));
    }

    public function update(Request $request, ActividadHorario $horario)
    {
        $request->validate([
            'actividad_id' => 'required|exists:actividades,id',
            'dia_semana' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'cupo_maximo' => 'required|integer|min:1',
            'instructor_id' => 'required|exists:instructors,id',
            'sala_id' => 'required|exists:salas,id',
            'estado' => 'required|boolean'
        ]);

        try {
            $horario->update($request->all());
            return redirect()->route('actividad_horarios.index')
                ->with('success', 'Horario actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(ActividadHorario $horario)
    {
        try {
            $horario->delete();
            return response()->json([
                'success' => true,
                'message' => 'Horario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}
