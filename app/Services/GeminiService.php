<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $endpoint;
    protected string $apiKey;

    public function __construct()
    {
        $this->endpoint = config('services.gemini.url');
        $this->apiKey = config('services.gemini.key');

        if (!$this->endpoint || !$this->apiKey) {
            throw new \Exception('Falta configuración de Gemini en config/services.php o .env');
        }
    }

    public function generate(string $prompt): string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
                throw new \Exception('Error al conectar con Gemini: ' . $response->body());
            }

            $data = $response->json();

            return $data['candidates'][0]['content']['parts'][0]['text']
                ?? 'No se obtuvo respuesta del modelo.';
        } catch (\Exception $e) {
            Log::error('Excepción en GeminiService', ['error' => $e->getMessage()]);
            return 'Hubo un error al procesar tu mensaje.';
        }
    }
}
