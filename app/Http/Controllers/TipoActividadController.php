<?php

namespace App\Http\Controllers;

use App\Models\TipoActividad;
use Illuminate\Http\Request;

class TipoActividadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_actividad,nombre',
            'descripcion' => 'nullable|string'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este tipo de actividad ya existe'
        ]);

        try {
            $tipo = TipoActividad::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Tipo de actividad creado exitosamente',
                'data' => $tipo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, TipoActividad $tipoActividad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_actividad,nombre,' . $tipoActividad->id,
            'descripcion' => 'nullable|string'
        ]);

        try {
            $tipoActividad->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Tipo de actividad actualizado exitosamente',
                'data' => $tipoActividad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(TipoActividad $tipoActividad)
    {
        try {
            if ($tipoActividad->actividades()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar. Existen actividades asociadas a este tipo.'
                ], 400);
            }

            $tipoActividad->delete();
            return response()->json([
                'success' => true,
                'message' => 'Tipo de actividad eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}
