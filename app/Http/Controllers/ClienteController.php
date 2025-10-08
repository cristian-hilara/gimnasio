<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
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
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());

            $cliente->save();

            DB::commit();

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente registrado exitosamente');
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
     * Generar código QR
     */
    public function generateQR($id)
    {
        $cliente = Cliente::findOrFail($id);

        if (!$cliente->codigoQR) {
            $cliente->codigoQR = 'CLI-' . strtoupper(uniqid());
            $cliente->save();
        }

        $qrCode = QrCode::size(300)->generate($cliente->codigoQR);

        return view('clientes.qr', compact('cliente', 'qrCode'));
    }
}
