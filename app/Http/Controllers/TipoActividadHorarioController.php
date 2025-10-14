<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadHorarioRequest;
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

    public function store(ActividadHorarioRequest $request)
    {
        
        try {
            ActividadHorario::create([
                'actividad_id' => $request->actividad_id,
                'dia_semana' => $request->dia_semana,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'cupo_maximo' => $request->cupo_maximo,
                'instructor_id' => $request->instructor_id,
                'sala_id' => $request->sala_id,
                'estado' => $request->estado
            ]);

            return redirect()->route('actividad_horarios.index')
                ->with('success', 'Horario de actividad creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el horario: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(ActividadHorario $actividad_horario)
    {
        $actividad_horario->load(['actividad.tipoActividad', 'instructor.usuario', 'sala']);
        return response()->json($actividad_horario);
    }

    public function edit(ActividadHorario $actividad_horario)
    {
        $actividades = Actividad::with('tipoActividad')->get();
        $instructores = Instructor::with('usuario')->where('estado', 'activo')->get();
        $salas = Sala::where('estado', 'disponible')->get();
        
        return view('actividades.horarios.edit', compact('actividad_horario', 'actividades', 'instructores', 'salas'));
    }

    public function update(ActividadHorarioRequest $request, ActividadHorario $actividad_horario)
    {
        try {
            $actividad_horario->update([
                'actividad_id' => $request->actividad_id,
                'dia_semana' => $request->dia_semana,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'cupo_maximo' => $request->cupo_maximo,
                'instructor_id' => $request->instructor_id,
                'sala_id' => $request->sala_id,
                'estado' => $request->estado
            ]);
            return redirect()->route('actividad_horarios.index')
                ->with('success', 'Horario actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(ActividadHorario $actividad_horario)
    {
        try {
            $actividad_horario->delete();
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
