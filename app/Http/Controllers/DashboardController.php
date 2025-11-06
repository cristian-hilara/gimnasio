<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Cliente;
use App\Models\HistorialMembresia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $rol = Auth::user()->getRoleNames()->first();

        $clientesActivos = Cliente::where('estado', 'activo')->count();

        $membresiasPorVencer = HistorialMembresia::where('estado_membresia', 'vigente')
            ->whereDate('fecha_fin', '<=', now()->addDays(7))
            ->count();

        $membresiasVencidas = HistorialMembresia::where('estado_membresia', 'vencida')->count();

        $actividadesHoy = Actividad::whereDate('fecha', now())->count();

        $inscripcionesPorMes = HistorialMembresia::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->groupBy('mes')->orderBy('mes')->get();

        $clientes = Cliente::with('usuario', 'historialMembresias')->get();

        return view('panel.index', compact(
            'rol',
            'clientesActivos',
            'membresiasPorVencer',
            'membresiasVencidas',
            'actividadesHoy',
            'inscripcionesPorMes',
            'clientes'
        ));
    }
}
