<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\HistorialMembresia;
use App\Models\Membresia;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{

    /**
     * 
     */
    public function create(Request $request)
    {
        $clienteId = $request->input('cliente_id'); // viene desde el botón "Inscribir"
        $clientes = Cliente::with('usuario')->where('estado', 'activo')->get();
        $membresias = Membresia::where('estado', true)->get();

        return view('inscripciones.create', compact('clientes', 'membresias', 'clienteId'));
    }


    /**
     * Registrar inscripción como la compra de membresía
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'membresia_id' => 'required|exists:membresias,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,qr',
            'referencia_pago' => 'nullable|string|max:255',
            'fecha_inicio' => 'nullable|date'
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente',
            'membresia_id.required' => 'Debe seleccionar una membresía',
            'metodo_pago.required' => 'Debe seleccionar un método de pago'
        ]);

        try {
            $historial = DB::transaction(function () use ($request) {
                $cliente = Cliente::findOrFail($request->cliente_id);
                $membresia = Membresia::findOrFail($request->membresia_id);

                // Buscar promoción activa para esta membresía
                $promocion = $membresia->promociones()
                    ->where('activa', true)
                    ->whereDate('fecha_inicio', '<=', now())
                    ->whereDate('fecha_fin', '>=', now())
                    ->first();

                $precioOriginal = $membresia->precio;
                $precioFinal = $promocion ? $promocion->pivot->precio_promocional : $precioOriginal;
                $descuento = $precioOriginal - $precioFinal;

                // Calcular fechas
                $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : today();
                $fechaFin = $fechaInicio->copy()->addDays($membresia->duracion_dias);

                // Crear historial de membresía
                $historial = HistorialMembresia::create([
                    'cliente_id' => $cliente->id,
                    'membresia_id' => $membresia->id,
                    'promocion_id' => $promocion?->id,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'estado_membresia' => 'vigente',
                    'precio_original' => $precioOriginal,
                    'descuento_aplicado' => $descuento,
                    'precio_final' => $precioFinal,
                ]);

                // Registrar pago
                Pago::create([
                    'cliente_id' => $cliente->id,
                    'historial_membresia_id' => $historial->id,
                    'fecha_pago' => today(),
                    'monto' => $precioFinal,
                    'metodo_pago' => $request->metodo_pago,
                    'referencia_pago' => $request->referencia_pago,
                ]);

                return $historial;
            });

            return redirect()->route('historial-membresias.index')
                ->with('success', 'Inscripción registrada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar la inscripción: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Obtener precio de membresía con promoción
     */
    public function getPrecioMembresia($membresiaId)
    {
        try {
            $membresia = Membresia::findOrFail($membresiaId);

            // Buscar promoción activa
            $promocion = $membresia->promociones()
                ->where('activa', true)
                ->whereDate('fecha_inicio', '<=', now())
                ->whereDate('fecha_fin', '>=', now())
                ->first();

            $precioOriginal = $membresia->precio;
            $precioFinal = $promocion ? $promocion->pivot->precio_promocional : $precioOriginal;
            $descuento = $precioOriginal - $precioFinal;
            $porcentajeDescuento = $precioOriginal > 0 ? round(($descuento / $precioOriginal) * 100, 2) : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'precio_original' => $precioOriginal,
                    'precio_final' => $precioFinal,
                    'descuento' => $descuento,
                    'porcentaje_descuento' => $porcentajeDescuento,
                    'tiene_promocion' => $promocion !== null,
                    'promocion' => $promocion ? [
                        'id' => $promocion->id,
                        'nombre' => $promocion->nombre,
                        'fecha_fin' => $promocion->fecha_fin->format('d/m/Y')
                    ] : null,
                    'duracion_dias' => $membresia->duracion_dias,
                    'fecha_fin_estimada' => now()->addDays($membresia->duracion_dias)->format('d/m/Y')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el precio'
            ], 500);
        }
    }
}
