<?php

namespace App\Http\Controllers;

use App\Models\HistorialMembresia;
use Illuminate\Http\Request;

class HistorialMembresiaController extends Controller
{
      public function index()
    {
        $historiales = HistorialMembresia::with(['cliente.usuario', 'membresia', 'promocion', 'pagos'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('inscripciones.historialmembresias.index', compact('historiales'));
    }

    public function show(HistorialMembresia $historialMembresia)
    {
        $historialMembresia->load(['cliente.usuario', 'membresia', 'promocion', 'pagos']);
        return response()->json($historialMembresia);
    }

    public function edit(HistorialMembresia $historialMembresia)
    {
        return view('inscripciones.historialmembresias.edit', compact('historialMembresia'));
    }

    public function update(Request $request, HistorialMembresia $historialMembresia)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado_membresia' => 'required|in:vigente,vencida,suspendida'
        ], [
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_fin.required' => 'La fecha de fin es obligatoria',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            'estado_membresia.required' => 'El estado es obligatorio'
        ]);

        try {
            $historialMembresia->update([
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'estado_membresia' => $request->estado_membresia
            ]);

            return redirect()->route('historial-membresias.index')
                ->with('success', 'Historial actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(HistorialMembresia $historialMembresia)
    {
        try {
            // Verificar si tiene pagos asociados
            if ($historialMembresia->pagos()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar. Tiene pagos asociados.'
                ], 400);
            }

            $historialMembresia->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Historial eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suspender membresía
     */
    public function suspend(HistorialMembresia $historialMembresia)
    {
        try {
            $historialMembresia->update(['estado_membresia' => 'suspendida']);
            
            return response()->json([
                'success' => true,
                'message' => 'Membresía suspendida exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al suspender: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reactivar membresía
     */
    public function reactivate(HistorialMembresia $historialMembresia)
    {
        try {
            $historialMembresia->update(['estado_membresia' => 'vigente']);
            
            return response()->json([
                'success' => true,
                'message' => 'Membresía reactivada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reactivar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener historiales por cliente
     */
    public function porCliente($clienteId)
    {
        $historiales = HistorialMembresia::with(['membresia', 'promocion', 'pagos'])
            ->where('cliente_id', $clienteId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $historiales
        ]);
    }

    /**
     * Actualizar estados vencidos (comando o cron)
     */
    public function actualizarEstados()
    {
        try {
            $actualizados = HistorialMembresia::where('estado_membresia', 'vigente')
                ->where('fecha_fin', '<', now())
                ->update(['estado_membresia' => 'vencida']);

            return response()->json([
                'success' => true,
                'message' => "$actualizados membresías actualizadas a vencidas"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estados: ' . $e->getMessage()
            ], 500);
        }
    }
}
