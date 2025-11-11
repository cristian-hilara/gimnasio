<?php

namespace App\Http\Controllers;

use App\Models\ActividadHorario;
use App\Models\ChatHistorial;
use App\Services\GeminiService;
use Auth;
use Http;
use Illuminate\Http\Request;

class ClienteChatController extends Controller
{

    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function index()
    {
        return view('chatbot.chatcliente');
    }

    public function responder(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $mensaje = $request->input('message');

        $cliente = Auth::user()->cliente;
        $usuario = $cliente->usuario; // relación cliente -> usuario

        // Datos del usuario
        $nombreCompleto = $usuario ? $usuario->nombre . ' ' . $usuario->apellido : 'Cliente';
        $telefono = $usuario ? $usuario->telefono : 'no registrado';
        $foto = $usuario ? $usuario->foto : 'sin foto';

        // Datos del cliente
        $peso = $cliente->peso ?? 'no registrado';
        $altura = $cliente->altura ?? 'no registrada';
        $codigoQR = $cliente->codigoQR ?? 'sin código';

        // Membresía actual
        $membresia = $cliente->historialMembresias()->latest()->first();
        $estadoMembresia = $membresia ? $membresia->estado_membresia : 'sin membresía';

        // Rutina activa
        $rutinaActiva = $cliente->rutinas()->where('estado', 'activa')->first();
        $nombreRutina = $rutinaActiva ? $rutinaActiva->nombre : 'ninguna';

        // Actividades disponibles
        $actividades = ActividadHorario::with(['actividad', 'instructor.usuario', 'sala'])
            ->where('estado', true)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $listaActividades = $actividades->map(function ($act) {
            $actividad = $act->actividad ? $act->actividad->nombre : 'Sin actividad';
            $dia = $act->dia_nombre;
            $hora = $act->hora_inicio ? $act->hora_inicio->format('H:i') : 'Sin hora';

            $instructor = $act->instructor && $act->instructor->usuario
                ? $act->instructor->usuario->nombre . ' ' . $act->instructor->usuario->apellido
                : 'Instructor no asignado';

            $sala = $act->sala ? $act->sala->nombre : 'Sala no asignada';

            return "{$actividad} ({$dia} {$hora}) - Instructor: {$instructor}, Sala: {$sala}";
        })->implode("\n");


        // Historial reciente (últimos 5 mensajes)
        $historialTexto = ChatHistorial::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($h) => "Cliente: {$h->mensaje}\nBot: {$h->respuesta}")
            ->implode("\n");

        // Prompt motivacional con datos completos
        $prompt = "
Eres un asistente personal de gimnasio para clientes.
Tu rol es responder preguntas sobre actividades, horarios, rutinas y estado de membresía.
Además, debes actuar como un entrenador motivacional: anima al cliente, celebra sus logros y ofrece consejos prácticos de salud y entrenamiento.
Usa un tono positivo, cercano y motivador, como un coach que quiere que el cliente se supere.
Responde de forma breve, clara y útil, evitando tecnicismos innecesarios.
Siempre incluye un mensaje motivacional al final.
IMPORTANTE: Divide tu respuesta en 2 a 3 mensajes cortos separados por '||'. 
Cada mensaje debe ser breve, claro y motivador.

Datos del cliente:
- Nombre: {$nombreCompleto}
- Teléfono: {$telefono}
- Foto: {$foto}
- Peso: {$peso} kg
- Altura: {$altura} cm
- Código QR: {$codigoQR}
- Membresía: {$estadoMembresia}
- Rutina activa: {$nombreRutina}
- Actividades disponibles: {$listaActividades}

Historial reciente:
{$historialTexto}

Pregunta actual del cliente: {$mensaje}
";

        try {
            $reply = $this->gemini->generate($prompt);

            // Dividir en array de mensajes
            $mensajes = explode('||', $reply);

            // Guardar en historial (texto completo)
            ChatHistorial::create([
                'cliente_id' => $cliente->id,
                'mensaje' => $mensaje,
                'respuesta' => $reply
            ]);

            // Mantener solo los últimos 20 mensajes por cliente
            ChatHistorial::where('cliente_id', $cliente->id)
                ->orderBy('created_at', 'desc')
                ->skip(20)
                ->take(PHP_INT_MAX)
                ->delete();

            return response()->json(['ok' => true, 'reply' => $mensajes]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}
