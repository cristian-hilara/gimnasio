<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\Promocion;
use Illuminate\Http\Request;

class PromocionMembresiaController extends Controller
{
      public function index($promocionId)
    {
        try {
            $promocion = Promocion::with('membresias')->findOrFail($promocionId);
            return response()->json([
                'success' => true,
                'data' => $promocion->membresias
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las membresías'
            ], 500);
        }
    }

    /**
     * Asociar una membresía a una promoción
     */
    public function store(Request $request, $promocionId)
    {
        $request->validate([
            'membresia_id' => 'required|exists:membresias,id',
            'precio_promocional' => 'required|numeric|min:0'
        ], [
            'membresia_id.required' => 'Debe seleccionar una membresía',
            'membresia_id.exists' => 'La membresía seleccionada no existe',
            'precio_promocional.required' => 'El precio promocional es obligatorio',
            'precio_promocional.numeric' => 'El precio debe ser un número',
            'precio_promocional.min' => 'El precio no puede ser negativo'
        ]);

        try {
            $promocion = Promocion::findOrFail($promocionId);
            
            // Verificar si ya existe la relación
            $existe = $promocion->membresias()
                ->where('membresia_id', $request->membresia_id)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta membresía ya está asociada a la promoción'
                ], 400);
            }

            // Asociar la membresía con el precio promocional
            $promocion->membresias()->attach($request->membresia_id, [
                'precio_promocional' => $request->precio_promocional
            ]);

            // Obtener la membresía con los datos del pivot
            $membresia = Membresia::find($request->membresia_id);
            $membresiaConPivot = $promocion->membresias()
                ->where('membresia_id', $request->membresia_id)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Membresía asociada exitosamente',
                'data' => $membresiaConPivot
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asociar la membresía: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar el precio promocional de una membresía en una promoción
     */
    public function update(Request $request, $promocionId, $membresiaId)
    {
        $request->validate([
            'precio_promocional' => 'required|numeric|min:0'
        ], [
            'precio_promocional.required' => 'El precio promocional es obligatorio',
            'precio_promocional.numeric' => 'El precio debe ser un número',
            'precio_promocional.min' => 'El precio no puede ser negativo'
        ]);

        try {
            $promocion = Promocion::findOrFail($promocionId);
            
            // Verificar que existe la relación
            if (!$promocion->membresias()->where('membresia_id', $membresiaId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta membresía no está asociada a la promoción'
                ], 404);
            }

            // Actualizar el precio promocional
            $promocion->membresias()->updateExistingPivot($membresiaId, [
                'precio_promocional' => $request->precio_promocional
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Precio promocional actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desvincular una membresía de una promoción
     */
    public function destroy($promocionId, $membresiaId)
    {
        try {
            $promocion = Promocion::findOrFail($promocionId);
            
            // Verificar que existe la relación
            if (!$promocion->membresias()->where('membresia_id', $membresiaId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta membresía no está asociada a la promoción'
                ], 404);
            }

            // Desvincular la membresía
            $promocion->membresias()->detach($membresiaId);

            return response()->json([
                'success' => true,
                'message' => 'Membresía desvinculada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desvincular: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener membresías disponibles para asociar (que no estén ya asociadas)
     */
    public function disponibles($promocionId)
    {
        try {
            $promocion = Promocion::findOrFail($promocionId);
            
            // Obtener IDs de membresías ya asociadas
            $membresiaAsociadas = $promocion->membresias()->pluck('membresias.id');
            
            // Obtener membresías activas que NO están asociadas
            $membresiasDisponibles = Membresia::where('estado', true)
                ->whereNotIn('id', $membresiaAsociadas)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $membresiasDisponibles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar membresías disponibles'
            ], 500);
        }
    }
}
