<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteNotificacionController extends Controller
{
      public function obtener()
    {
        $user = Auth::user();
        if (!$user || !$user->cliente) {
            return response()->json(['ok' => false, 'mensaje' => null]);
        }

        $cliente = $user->cliente;
        $membresia = $cliente->historialMembresias()->latest()->first();

        if (!$membresia || !$membresia->fecha_fin) {
            return response()->json(['ok' => true, 'mensaje' => null]); // no hay aviso
        }

        $fechaFin = $membresia->fecha_fin instanceof \Carbon\Carbon
            ? $membresia->fecha_fin
            : \Carbon\Carbon::parse($membresia->fecha_fin);

        $hoy = now()->startOfDay();
        $fin = $fechaFin->startOfDay();
        $diff = $hoy->diffInDays($fin, false); // negativo si ya venció

        $mensaje = null;

        if ($diff === 0) {
            $mensaje = "⏳ Tu membresía termina hoy. Por favor, acércate a recepción para renovarla.";
        } elseif ($diff === 1) {
            $mensaje = "⏰ Tu membresía termina mañana. No olvides renovarla con recepción.";
        } elseif ($diff < 0) {
            $mensaje = "❌ Tu membresía ya terminó. Ve a recepción para renovarla.";
        } elseif ($diff <= 5) {
            $mensaje = "⚠️ Tu membresía vence en {$diff} día(s). Te recomendamos renovarla pronto.";
        }

        return response()->json([
            'ok' => true,
            'mensaje' => $mensaje
        ]);
    }
}
