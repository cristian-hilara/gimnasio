<?php

namespace App\Http\Controllers;

use App\Models\Ejercicio;
use App\Models\Rutina;
use Auth;
use Illuminate\Http\Request;

class RutinaController extends Controller
{
    public function index()
    {
        $cliente = Auth::user()->cliente;
        $rutinas = $cliente->rutinas()->with('ejercicios')->get();

        return view('clientes.rutinas.index', compact('rutinas'));
    }

    /**
     * Mostrar formulario para crear rutina
     */
    public function create()
    {
        $ejercicios = Ejercicio::all();
        return view('clientes.rutinas.create', compact('ejercicios'));
    }

    /**
     * Guardar nueva rutina
     */
    public function store(Request $request)
    {
        // Validación base
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:personalizada,weider,push_pull_legs,full_body,femenina',
        ]);

        $cliente = Auth::user()->cliente;

        // Crear rutina
        $rutina = $cliente->rutinas()->create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? '',
            'estado' => 'activa',
        ]);

        // Generación según tipo
        switch ($request->tipo) {
            case 'weider':
                $this->generarWeider($rutina);
                break;
            case 'push_pull_legs':
                $this->generarPushPullLegs($rutina);
                break;
            case 'full_body':
                $this->generarFullBody($rutina);
                break;
            case 'femenina':
                $this->generarFemenina($rutina);
                break;
            case 'personalizada':
                // Procesar ejercicios del nuevo formato
                if ($request->has('ejercicios') && is_array($request->ejercicios)) {
                    foreach ($request->ejercicios as $key => $ejercicio) {
                        // Verificar que tenga los datos necesarios
                        if (
                            isset($ejercicio['ejercicio_id']) &&
                            isset($ejercicio['dia_semana']) &&
                            isset($ejercicio['series']) &&
                            isset($ejercicio['repeticiones'])
                        ) {

                            $rutina->ejercicios()->attach($ejercicio['ejercicio_id'], [
                                'series' => $ejercicio['series'],
                                'repeticiones' => $ejercicio['repeticiones'],
                                'peso' => $ejercicio['peso'] ?? null,
                                'dia_semana' => $ejercicio['dia_semana'],
                            ]);
                        }
                    }
                }

                // Verificar que se hayan agregado ejercicios
                if ($rutina->ejercicios()->count() === 0) {
                    $rutina->delete();
                    return back()->withErrors(['ejercicios' => 'Debes seleccionar al menos un ejercicio'])
                        ->withInput();
                }
                break;
        }

        return redirect()->route('cliente.rutinas.index', $rutina->id)
            ->with('success', 'Rutina creada correctamente');
    }

    /**
     * Mostrar una rutina específica
     */
    public function show($id)
    {
        $rutina = Rutina::with('ejercicios')->findOrFail($id);

        // Verificar que la rutina pertenezca al cliente
        if ($rutina->cliente_id !== Auth::user()->cliente->id) {
            abort(403, 'No tienes permiso para ver esta rutina');
        }

        return view('clientes.rutinas.show', compact('rutina'));
    }

    /**
     * Editar rutina
     */
    public function edit($id)
    {
        $rutina = Rutina::with('ejercicios')->findOrFail($id);

        // Verificar que la rutina pertenezca al cliente
        if ($rutina->cliente_id !== Auth::user()->cliente->id) {
            abort(403, 'No tienes permiso para editar esta rutina');
        }

        $ejercicios = Ejercicio::all();
        return view('clientes.rutinas.edit', compact('rutina', 'ejercicios'));
    }

    /**
     * Actualizar rutina
     */
    public function update(Request $request, $id)
    {
        $rutina = Rutina::findOrFail($id);

        // Verificar que la rutina pertenezca al cliente
        if ($rutina->cliente_id !== Auth::user()->cliente->id) {
            abort(403, 'No tienes permiso para actualizar esta rutina');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activa,inactiva',
        ]);

        $rutina->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'estado' => $validated['estado'],
        ]);

        // Actualizar ejercicios
        if ($request->has('ejercicios') && is_array($request->ejercicios)) {
            // Primero, eliminar todos los ejercicios actuales
            $rutina->ejercicios()->detach();

            // Luego, agregar los nuevos ejercicios
            foreach ($request->ejercicios as $key => $ejercicio) {
                // Verificar que tenga los datos necesarios
                if (
                    isset($ejercicio['ejercicio_id']) &&
                    isset($ejercicio['dia_semana']) &&
                    isset($ejercicio['series']) &&
                    isset($ejercicio['repeticiones'])
                ) {

                    $rutina->ejercicios()->attach($ejercicio['ejercicio_id'], [
                        'series' => $ejercicio['series'],
                        'repeticiones' => $ejercicio['repeticiones'],
                        'peso' => $ejercicio['peso'] ?? null,
                        'dia_semana' => $ejercicio['dia_semana'],
                    ]);
                }
            }
        } else {
            // Si no se envían ejercicios, eliminar todos
            $rutina->ejercicios()->detach();
        }

        // Verificar que tenga al menos un ejercicio
        if ($rutina->ejercicios()->count() === 0) {
            return back()->withErrors(['ejercicios' => 'La rutina debe tener al menos un ejercicio'])
                ->withInput();
        }

        return redirect()->route('cliente.rutinas.show', $rutina->id)
            ->with('success', 'Rutina actualizada correctamente');
    }

    /**
     * Eliminar rutina
     */
    public function destroy($id)
    {
        $rutina = Rutina::findOrFail($id);

        // Verificar que la rutina pertenezca al cliente
        if ($rutina->cliente_id !== Auth::user()->cliente->id) {
            abort(403, 'No tienes permiso para eliminar esta rutina');
        }

        $rutina->delete();

        return redirect()->route('cliente.rutinas.index')
            ->with('success', 'Rutina eliminada correctamente');
    }

    // -----------------------------
    // Métodos de generación automática
    // -----------------------------

    private function generarWeider($rutina)
    {
        // Obtener ejercicios por grupo muscular
        $pecho = Ejercicio::where('grupo_muscular', 'Pecho')->first();
        $espalda = Ejercicio::where('grupo_muscular', 'Espalda')->first();
        $piernas = Ejercicio::where('grupo_muscular', 'Piernas')->first();
        $hombros = Ejercicio::where('grupo_muscular', 'Hombros')->first();
        $brazos = Ejercicio::where('grupo_muscular', 'Brazos')->first();

        $ejercicios = [];

        if ($pecho) {
            $ejercicios[$pecho->id] = [
                'series' => 4,
                'repeticiones' => 10,
                'dia_semana' => 'lunes',
                'peso' => null
            ];
        }

        if ($espalda) {
            $ejercicios[$espalda->id] = [
                'series' => 4,
                'repeticiones' => 12,
                'dia_semana' => 'martes',
                'peso' => null
            ];
        }

        if ($piernas) {
            $ejercicios[$piernas->id] = [
                'series' => 5,
                'repeticiones' => 8,
                'dia_semana' => 'miercoles',
                'peso' => null
            ];
        }

        if ($hombros) {
            $ejercicios[$hombros->id] = [
                'series' => 4,
                'repeticiones' => 12,
                'dia_semana' => 'jueves',
                'peso' => null
            ];
        }

        if ($brazos) {
            $ejercicios[$brazos->id] = [
                'series' => 3,
                'repeticiones' => 15,
                'dia_semana' => 'viernes',
                'peso' => null
            ];
        }

        if (!empty($ejercicios)) {
            $rutina->ejercicios()->attach($ejercicios);
        }
    }

    private function generarPushPullLegs($rutina)
    {
        $push = Ejercicio::where('grupo_muscular', 'Pecho')->first();
        $pull = Ejercicio::where('grupo_muscular', 'Espalda')->first();
        $legs = Ejercicio::where('grupo_muscular', 'Piernas')->first();

        $ejercicios = [];

        if ($push) {
            $ejercicios[$push->id] = [
                'series' => 4,
                'repeticiones' => 10,
                'dia_semana' => 'lunes',
                'peso' => null
            ];
        }

        if ($pull) {
            $ejercicios[$pull->id] = [
                'series' => 4,
                'repeticiones' => 10,
                'dia_semana' => 'miercoles',
                'peso' => null
            ];
        }

        if ($legs) {
            $ejercicios[$legs->id] = [
                'series' => 5,
                'repeticiones' => 8,
                'dia_semana' => 'viernes',
                'peso' => null
            ];
        }

        if (!empty($ejercicios)) {
            $rutina->ejercicios()->attach($ejercicios);
        }
    }

    private function generarFullBody($rutina)
    {
        // Buscar ejercicios específicos
        $sentadillas = Ejercicio::where('nombre', 'LIKE', '%sentadilla%')->first();
        $pressBanca = Ejercicio::where('nombre', 'LIKE', '%press%banca%')->first();
        $dominadas = Ejercicio::where('nombre', 'LIKE', '%dominada%')->first();

        // Si no encuentra por nombre, buscar por grupo muscular
        if (!$sentadillas) {
            $sentadillas = Ejercicio::where('grupo_muscular', 'Piernas')->first();
        }
        if (!$pressBanca) {
            $pressBanca = Ejercicio::where('grupo_muscular', 'Pecho')->first();
        }
        if (!$dominadas) {
            $dominadas = Ejercicio::where('grupo_muscular', 'Espalda')->first();
        }

        $ejercicios = [];

        if ($sentadillas) {
            $ejercicios[$sentadillas->id] = [
                'series' => 4,
                'repeticiones' => 10,
                'dia_semana' => 'lunes',
                'peso' => null
            ];
        }

        if ($pressBanca) {
            $ejercicios[$pressBanca->id] = [
                'series' => 4,
                'repeticiones' => 12,
                'dia_semana' => 'lunes',
                'peso' => null
            ];
        }

        if ($dominadas) {
            $ejercicios[$dominadas->id] = [
                'series' => 4,
                'repeticiones' => 8,
                'dia_semana' => 'miercoles',
                'peso' => null
            ];
        }

        if (!empty($ejercicios)) {
            $rutina->ejercicios()->attach($ejercicios);
        }
    }

    private function generarFemenina($rutina)
    {
        $gluteos = Ejercicio::where('grupo_muscular', 'Glúteos')
            ->orWhere('grupo_muscular', 'LIKE', '%glute%')
            ->first();
        $piernas = Ejercicio::where('grupo_muscular', 'Piernas')->first();
        $abdomen = Ejercicio::where('grupo_muscular', 'Abdomen')
            ->orWhere('grupo_muscular', 'LIKE', '%core%')
            ->first();

        $ejercicios = [];

        if ($gluteos) {
            $ejercicios[$gluteos->id] = [
                'series' => 4,
                'repeticiones' => 15,
                'dia_semana' => 'lunes',
                'peso' => null
            ];
        }

        if ($piernas) {
            $ejercicios[$piernas->id] = [
                'series' => 4,
                'repeticiones' => 12,
                'dia_semana' => 'miercoles',
                'peso' => null
            ];
        }

        if ($abdomen) {
            $ejercicios[$abdomen->id] = [
                'series' => 4,
                'repeticiones' => 20,
                'dia_semana' => 'viernes',
                'peso' => null
            ];
        }

        if (!empty($ejercicios)) {
            $rutina->ejercicios()->attach($ejercicios);
        }
    }
}
