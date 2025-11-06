<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\HistorialMembresia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
     
    public function index(Request $request)
    {
        $query = Cliente::with([
            'usuario',
            'asistencias' => fn($q) => $q->latest()->limit(5),
            'historialMembresias' => function ($q) {
                $q->where('estado_membresia', 'vigente')
                    ->whereDate('fecha_fin', '>=', today())
                    ->with('membresia');
            }
        ]);

        // Filtros
        if ($request->filled('estado')) {
            if ($request->estado == 'vigente') {
                $query->whereHas('historialMembresias', function($q) {
                    $q->where('estado_membresia', 'vigente')
                        ->whereDate('fecha_fin', '>=', today());
                });
            } elseif ($request->estado == 'vencida') {
                $query->whereDoesntHave('historialMembresias', function($q) {
                    $q->where('estado_membresia', 'vigente')
                        ->whereDate('fecha_fin', '>=', today());
                });
            }
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('usuario', function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('apellido', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%")
                    ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        $clientes = $query->paginate(20);

        // Estadísticas del día
        $estadisticas = [
            'total_hoy' => Asistencia::whereDate('fecha', today())->count(),
            'en_gimnasio' => Asistencia::whereDate('fecha', today())
                ->whereNull('hora_salida')
                ->count(),
            'total_clientes' => Cliente::count(),
            'membresias_vigentes' => HistorialMembresia::where('estado_membresia', 'vigente')
                ->whereDate('fecha_fin', '>=', today())
                ->count()
        ];

        return view('asistencias.index', compact('clientes', 'estadisticas'));
    }

    /**
     * Registrar asistencia manual
     */
    public function registrarManual($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);

            // Verificar membresía vigente
            $membresiaVigente = HistorialMembresia::where('cliente_id', $cliente->id)
                ->where('estado_membresia', 'vigente')
                ->whereDate('fecha_fin', '>=', today())
                ->first();

            if (!$membresiaVigente) {
                return redirect()->back()->with('error', 'El cliente no tiene una membresía vigente.');
            }

            // Verificar si ya registró entrada hoy
            $asistenciaHoy = Asistencia::where('cliente_id', $cliente->id)
                ->whereDate('fecha', today())
                ->whereNull('hora_salida')
                ->first();

            if ($asistenciaHoy) {
                return redirect()->back()->with('warning', 'El cliente ya tiene una entrada registrada hoy.');
            }

            Asistencia::create([
                'cliente_id' => $cliente->id,
                'fecha' => today(),
                'hora_entrada' => now()->format('H:i:s'),
                'origen' => 'manual',
            ]);

            return redirect()->back()->with('success', 'Asistencia registrada correctamente para ' . $cliente->usuario->nombre);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Registrar salida
     */
    public function registrarSalida($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);

            $asistencia = Asistencia::where('cliente_id', $cliente->id)
                ->whereDate('fecha', today())
                ->whereNull('hora_salida')
                ->first();

            if (!$asistencia) {
                return redirect()->back()->with('error', 'No hay entrada registrada para este cliente hoy.');
            }

            $asistencia->update([
                'hora_salida' => now()->format('H:i:s')
            ]);

            return redirect()->back()->with('success', 'Salida registrada correctamente para ' . $cliente->usuario->nombre);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar salida: ' . $e->getMessage());
        }
    }

    /**
     * Scanner QR - Vista
     */
    public function scanner()
    {
        return view('asistencias.scanner');
    }

    /**
     * Registrar asistencia por QR
     */
    public function registrarQR(Request $request)
    {
        $request->validate([
            'codigo_qr' => 'required|string'
        ]);

        try {
            // Buscar cliente por código QR
            $cliente = Cliente::where('codigoQR', $request->codigo_qr)->first();

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código QR no válido'
                ], 404);
            }

            // Verificar membresía vigente
            $membresiaVigente = HistorialMembresia::where('cliente_id', $cliente->id)
                ->where('estado_membresia', 'vigente')
                ->whereDate('fecha_fin', '>=', today())
                ->first();

            if (!$membresiaVigente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Membresía vencida',
                    'cliente' => [
                        'nombre' => $cliente->usuario->nombre . ' ' . $cliente->usuario->apellido,
                        'estado' => 'vencida'
                    ]
                ], 403);
            }

            // Verificar si ya registró entrada hoy
            $asistenciaHoy = Asistencia::where('cliente_id', $cliente->id)
                ->whereDate('fecha', today())
                ->whereNull('hora_salida')
                ->first();

            if ($asistenciaHoy) {
                // Registrar salida
                $asistenciaHoy->update([
                    'hora_salida' => now()->format('H:i:s')
                ]);

                return response()->json([
                    'success' => true,
                    'tipo' => 'salida',
                    'message' => '¡Hasta pronto!',
                    'cliente' => [
                        'nombre' => $cliente->usuario->nombre . ' ' . $cliente->usuario->apellido,
                        'foto' => $cliente->usuario->foto ? asset('storage/' . $cliente->usuario->foto) : null,
                        'hora_entrada' => $asistenciaHoy->hora_entrada,
                        'hora_salida' => now()->format('H:i:s'),
                        'duracion' => $asistenciaHoy->duracion
                    ]
                ]);
            }

            // Registrar entrada
            Asistencia::create([
                'cliente_id' => $cliente->id,
                'fecha' => today(),
                'hora_entrada' => now()->format('H:i:s'),
                'origen' => 'qr',
            ]);

            return response()->json([
                'success' => true,
                'tipo' => 'entrada',
                'message' => '¡Bienvenido!',
                'cliente' => [
                    'nombre' => $cliente->usuario->nombre . ' ' . $cliente->usuario->apellido,
                    'foto' => $cliente->usuario->foto ? asset('storage/' . $cliente->usuario->foto) : null,
                    'membresia' => $membresiaVigente->membresia->nombre,
                    'dias_restantes' => $membresiaVigente->dias_restantes,
                    'hora_entrada' => now()->format('H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el código: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Historial de asistencias
     */
    public function historial(Request $request)
    {
        $query = Asistencia::with(['cliente.usuario'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc');

        // Filtros
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        $asistencias = $query->paginate(50);
        $clientes = Cliente::with('usuario')->get();

        return view('asistencias.historial', compact('asistencias', 'clientes'));
    }

    /**
     * Verificar estado del cliente (para modal rápido)
     */
    public function verificarCliente($id)
    {
        $cliente = Cliente::with(['usuario', 'historialMembresias' => function($q) {
            $q->where('estado_membresia', 'vigente')
                ->whereDate('fecha_fin', '>=', today())
                ->with('membresia');
        }])->findOrFail($id);

        $membresiaVigente = $cliente->historialMembresias->first();
        $asistenciaHoy = $cliente->asistenciaHoy;

        return response()->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente->id,
                'nombre' => $cliente->usuario->nombre . ' ' . $cliente->usuario->apellido,
                'foto' => $cliente->usuario->foto ? asset('storage/' . $cliente->usuario->foto) : null,
                'tiene_membresia' => $membresiaVigente !== null,
                'membresia' => $membresiaVigente ? [
                    'nombre' => $membresiaVigente->membresia->nombre,
                    'fecha_fin' => $membresiaVigente->fecha_fin->format('d/m/Y'),
                    'dias_restantes' => $membresiaVigente->dias_restantes
                ] : null,
                'asistencia_hoy' => $asistenciaHoy ? [
                    'hora_entrada' => $asistenciaHoy->hora_entrada,
                    'en_gimnasio' => $asistenciaHoy->en_gimnasio
                ] : null
            ]
        ]);
    }
}
