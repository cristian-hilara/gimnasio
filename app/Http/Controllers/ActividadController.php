<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActividadRequest;
use App\Http\Requests\UpdateActividadRequest;
use App\Models\Actividad;
use App\Models\Instructor;
use App\Models\Sala;
use App\Models\TipoActividad;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actividades = Actividad::with('tipoActividad')->get();
        $tiposActividad = TipoActividad::all();
        return view('actividades.actividades.index', compact('actividades', 'tiposActividad'));
    }

    public function create()
    {
        $tiposActividad = TipoActividad::all();
        return view('actividades.actividades.create', compact('tiposActividad'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_actividad_id' => 'required|exists:tipos_actividad,id'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'tipo_actividad_id.required' => 'Debe seleccionar un tipo de actividad',
            'tipo_actividad_id.exists' => 'El tipo de actividad seleccionado no existe'
        ]);

        try {
            Actividad::create($request->all());
            return redirect()->route('actividades.index')
                ->with('success', 'Actividad creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la actividad: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Actividad $actividad)
    {
        $actividad->load('tipoActividad', 'horarios');
        return response()->json($actividad);
    }

    public function edit(Actividad $actividade)
    {
        $tiposActividad = TipoActividad::all();
        return view('actividades.actividades.edit', compact('actividade', 'tiposActividad'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_actividad_id' => 'required|exists:tipos_actividad,id'
        ]);

        try {
            $actividad->update($request->all());
            return redirect()->route('actividades.index')
                ->with('success', 'Actividad actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la actividad: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Actividad $actividad)
    {
        try {
            $actividad->delete();
            return response()->json([
                'success' => true,
                'message' => 'Actividad eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}
