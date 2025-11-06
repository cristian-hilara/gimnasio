<?php

namespace App\Http\Controllers;

use App\Models\ActividadHorario;
use App\Models\Membresia;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function index()
    {
        return view('chatbot.chat');
    }


    public function testSSL()
    {
        $response = Http::get('https://www.google.com');
        dd($response->status()); // Debería devolver 200 si todo está bien
    }

    public function responder(Request $request)
{
    $request->validate(['message' => 'required|string|max:1000']);
    $mensaje = strtolower($request->input('message'));

    // Consulta interna: membresías
    if (str_contains($mensaje, 'membresía')) {
        $membresias = Membresia::pluck('nombre')->toArray();
        return response()->json(['ok' => true, 'reply' => 'Las membresías disponibles son: ' . implode(', ', $membresias)]);
    }

    // Consulta interna: horarios
    if (str_contains($mensaje, 'horario')) {
        $horarios = ActividadHorario::all()->map(fn($h) => $h->dia . ': ' . $h->hora_inicio . ' - ' . $h->hora_fin)->toArray();
        return response()->json(['ok' => true, 'reply' => 'Nuestros horarios son: ' . implode('; ', $horarios)]);
    }

    // Gemini como fallback
    try {
        $reply = $this->gemini->generate($mensaje);
        return response()->json(['ok' => true, 'reply' => $reply]);
    } catch (\Exception $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()]);
    }
}

}
