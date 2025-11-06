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

    public function show(HistorialMembresia $historial_membresia)
    {
        $historial_membresia->load(['cliente.usuario', 'membresia', 'promocion', 'pagos']);

        $estadoBadge = match ($historial_membresia->estado_membresia) {
            'vigente' => ['clase' => 'bg-success', 'texto' => 'Vigente'],
            'vencida' => ['clase' => 'bg-secondary', 'texto' => 'Vencida'],
            'suspendida' => ['clase' => 'bg-warning', 'texto' => 'Suspendida'],
            default => ['clase' => 'bg-dark', 'texto' => ucfirst($historial_membresia->estado_membresia)],
        };

        $diasTotales = $historial_membresia->fecha_inicio->diffInDays($historial_membresia->fecha_fin);
        $diasRestantes = max(0, now()->diffInDays($historial_membresia->fecha_fin, false));
        $porcentajeProgreso = $diasTotales > 0 ? round((($diasTotales - $diasRestantes) / $diasTotales) * 100) : 100;

        return response()->json([
            'cliente' => $historial_membresia->cliente,
            'membresia' => $historial_membresia->membresia,
            'promocion' => $historial_membresia->promocion,
            'pagos' => $historial_membresia->pagos,
            'fecha_inicio' => $historial_membresia->fecha_inicio,
            'fecha_fin' => $historial_membresia->fecha_fin,
            'estado_membresia' => $historial_membresia->estado_membresia,
            'estado_badge' => $estadoBadge,
            'precio_original' => $historial_membresia->precio_original,
            'descuento_aplicado' => $historial_membresia->descuento_aplicado,
            'precio_final' => $historial_membresia->precio_final,
            'dias_totales' => $diasTotales,
            'dias_restantes' => $diasRestantes,
            'porcentaje_progreso' => $porcentajeProgreso,
            'porcentaje_descuento' => $historial_membresia->precio_original > 0
                ? round(($historial_membresia->descuento_aplicado / $historial_membresia->precio_original) * 100, 2)
                : 0,
        ]);
    }


    public function edit(HistorialMembresia $historial_membresia)
    {
        return view('inscripciones.historialmembresias.edit', compact('historialMembresia'));
    }

    public function update(Request $request, HistorialMembresia $historial_membresia)
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
            $historial_membresia->update([
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

    public function destroy(HistorialMembresia $historial_membresia)
    {
        try {
            // Verificar si tiene pagos asociados
            if ($historial_membresia->pagos()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar. Tiene pagos asociados.'
                ], 400);
            }

            $historial_membresia->delete();

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
