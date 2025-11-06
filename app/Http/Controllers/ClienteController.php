<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClienteController extends Controller
{
    /**
     * Panel del cliente autenticado
     */
    public function dashboard()
    {
        if (!Auth::user()->hasRole('CLIENTE')) {
            abort(403, 'Acceso no autorizado');
        }

        $cliente = Cliente::where('usuario_id', Auth::user()->id)->first();

        if (!$cliente) {
            return redirect()->route('errors.cliente_no_registrado')
                ->with('warning', 'Tu perfil de cliente aún no está registrado.');
        }

        return view('panel.panel_cliente', compact('cliente'));
    }

    /**
     * Listado de clientes
     */
    public function index()
    {
        $clientes = Cliente::with('usuario')->get();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $usuarios = Usuario::role('CLIENTE')
            ->whereNotIn('id', function ($query) {
                $query->select('usuario_id')->from('clientes');
            })
            ->get();

        return view('clientes.create', compact('usuarios'));
    }

    /**
     * Guardar nuevo cliente
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id|unique:clientes,usuario_id',
            'peso' => 'nullable|numeric|min:0|max:999.99',
            'altura' => 'nullable|numeric|min:0|max:9.99',
            'estado' => 'required|in:activo,inactivo'
        ], [
            'usuario_id.required' => 'Debe seleccionar un usuario',
            'usuario_id.unique' => 'Este usuario ya está registrado como cliente',
            'peso.numeric' => 'El peso debe ser un número',
            'peso.max' => 'El peso no puede superar 999.99 kg',
            'altura.numeric' => 'La altura debe ser un número',
            'altura.max' => 'La altura no puede superar 9.99 m',
            'estado.required' => 'El estado es obligatorio'
        ]);

        try {
            DB::beginTransaction();

            $cliente = new Cliente();
            $cliente->usuario_id = $request->usuario_id;
            $cliente->peso = $request->peso;
            $cliente->altura = $request->altura;
            $cliente->estado = $request->estado;

            // 🆕 Generar código QR automáticamente
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());

            $cliente->save();

            DB::commit();

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente registrado exitosamente. Código QR generado.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar cliente (usado por AJAX)
     */
    public function show(Cliente $cliente)
    {
        $cliente->load('usuario');
        return response()->json($cliente);
    }

    /**
     * Formulario de edición
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar cliente
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'peso' => 'nullable|numeric|min:0|max:999.99',
            'altura' => 'nullable|numeric|min:0|max:9.99',
            'estado' => 'required|in:activo,inactivo'
        ], [
            'peso.numeric' => 'El peso debe ser un número',
            'altura.numeric' => 'La altura debe ser un número',
            'estado.required' => 'El estado es obligatorio'
        ]);

        try {
            DB::beginTransaction();

            $cliente->peso = $request->peso;
            $cliente->altura = $request->altura;
            $cliente->estado = $request->estado;

            $cliente->save();

            DB::commit();

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente actualizado exitosamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar cliente
     */
    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado exitosamente'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver perfil del cliente con QR
     */
    public function perfil($id)
    {
        $cliente = Cliente::with([
            'usuario',
            'historialMembresias' => function ($q) {
                $q->where('estado_membresia', 'vigente')
                    ->whereDate('fecha_fin', '>=', today())
                    ->with('membresia');
            }
        ])->findOrFail($id);

        // Generar QR si no existe
        if (!$cliente->codigoQR) {
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());
            $cliente->save();
        }

        return view('clientes.perfil', compact('cliente'));
    }

    /**
     * Descargar QR en formato PNG
     */
    public function descargarQR($id)
    {
        $cliente = Cliente::with('usuario')->findOrFail($id);

        // Generar QR si no existe
        if (!$cliente->codigoQR) {
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());
            $cliente->save();
        }

        // Generar QR de alta calidad
        $qr = QrCode::size(500)
            ->format('png')
            ->margin(2)
            ->errorCorrection('H')
            ->generate($cliente->codigoQR);

        return response($qr)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $cliente->usuario->nombre . '.png"');
    }

    /**
     * Ver tarjeta en navegador
     */
    public function verTarjeta($id)
    {
        $cliente = Cliente::with([
            'usuario',
            'historialMembresias' => function ($q) {
                $q->where('estado_membresia', 'vigente')
                    ->whereDate('fecha_fin', '>=', today())
                    ->with('membresia');
            }
        ])->findOrFail($id);

        // Generar QR si no existe
        if (!$cliente->codigoQR) {
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());
            $cliente->save();
        }

        $membresiaVigente = $cliente->historialMembresias->first();

        // Generar QR en SVG
        $qrCode = QrCode::size(200)
            ->format('svg')
            ->errorCorrection('H')
            ->generate($cliente->codigoQR);

        return view('clientes.tarjeta', compact('cliente', 'membresiaVigente', 'qrCode'));
    }

    /**
     * Generar tarjeta de membresía en PDF
     */
    public function generarTarjeta($id)
    {
        $cliente = Cliente::with([
            'usuario',
            'historialMembresias' => function ($q) {
                $q->where('estado_membresia', 'vigente')
                    ->whereDate('fecha_fin', '>=', today())
                    ->with('membresia');
            }
        ])->findOrFail($id);

        // Generar QR si no existe
        if (!$cliente->codigoQR) {
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());
            $cliente->save();
        }

        $membresiaVigente = $cliente->historialMembresias->first();

        // Generar QR en base64 para PDF
        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->generate($cliente->codigoQR));

        $pdf = Pdf::loadView('clientes.tarjeta-pdf', compact('cliente', 'membresiaVigente', 'qrCode'));

        // Tamaño de tarjeta estándar (85.6mm x 53.98mm)
        $pdf->setPaper([0, 0, 242.65, 153], 'landscape');

        return $pdf->download('tarjeta-' . $cliente->usuario->nombre . '.pdf');
    }

    /**
     * Regenerar código QR
     */
    public function regenerarQR($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());
        $cliente->save();

        return redirect()->back()->with('success', 'Código QR regenerado exitosamente');
    }

    /**
     * Ver QR en pantalla (vista simple)
     */
    public function generateQR($id)
    {
        $cliente = Cliente::findOrFail($id);

        // Generar QR si no existe
        if (!$cliente->codigoQR) {
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());
            $cliente->save();
        }

        $qrCode = QrCode::size(300)->generate($cliente->codigoQR);

        return view('clientes.qr', compact('cliente', 'qrCode'));
    }

    /**
     *  Ver clientes sin QR generado
     */
    public function sinQR()
    {
        $clientes = Cliente::with('usuario')
            ->whereNull('codigoQR')
            ->orWhere('codigoQR', '')
            ->get();

        return view('clientes.sin-qr', compact('clientes'));
    }

    /**
     *  Generar códigos QR masivamente
     */
    public function generarQRMasivo()
    {
        $clientes = Cliente::whereNull('codigoQR')
            ->orWhere('codigoQR', '')
            ->get();

        foreach ($clientes as $cliente) {
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());
            $cliente->save();
        }

        return redirect()->back()
            ->with('success', "Se generaron {$clientes->count()} códigos QR exitosamente");
    }
}
