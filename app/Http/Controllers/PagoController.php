<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\HistorialMembresia;
use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['cliente.usuario', 'historialMembresia.membresia'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inscripciones.pagos.index', compact('pagos'));
    }

    public function create()
    {
        $clientes = Cliente::with('usuario')->where('estado', 'activo')->get();
        return view('inscripciones.pagos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'historial_membresia_id' => 'required|exists:historial_membresias,id',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,qr',
            'referencia_pago' => 'nullable|string|max:255'
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente',
            'historial_membresia_id.required' => 'Debe seleccionar una membresía',
            'monto.required' => 'El monto es obligatorio',
            'metodo_pago.required' => 'Debe seleccionar un método de pago'
        ]);

        try {
            Pago::create($request->all());

            return redirect()->route('pagos.index')
                ->with('success', 'Pago registrado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar el pago: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Pago $pago)
    {
        $pago->load(['cliente.usuario', 'historialMembresia.membresia']);
        return response()->json($pago);
    }

    public function edit(Pago $pago)
    {
        return view('inscripciones.pagos.edit', compact('pago'));
    }

    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,qr',
            'referencia_pago' => 'nullable|string|max:255'
        ]);

        try {
            $pago->update($request->all());

            return redirect()->route('pagos.index')
                ->with('success', 'Pago actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Pago $pago)
    {
        try {
            $pago->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pago eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener pagos por cliente
     */
    public function porCliente($clienteId)
    {
        $pagos = Pago::with(['historialMembresia.membresia'])
            ->where('cliente_id', $clienteId)
            ->orderBy('fecha_pago', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pagos
        ]);
    }

    /**
     * Obtener historiales de membresía de un cliente
     */
    public function getHistorialesCliente($clienteId)
    {
        $historiales = HistorialMembresia::with('membresia')
            ->where('cliente_id', $clienteId)
            ->where('estado_membresia', 'vigente')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $historiales
        ]);
    }
}
