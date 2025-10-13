<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salas = Sala::all();
        return view('actividades.salas.index', compact('salas'));
    }

    /**
     * Muestra el formulario para crear una nueva sala.
     */
    public function create()
    {
        return view('actividades.salas.create');
    }

    /**
     * Guarda una nueva sala en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'estado' => 'required|in:disponible,ocupada,mantenimiento',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'estado.required' => 'El campo estado es obligatorio.',
            'estado.in' => 'El estado debe ser disponible, ocupada o mantenimiento.',
            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.integer' => 'La capacidad debe ser un número entero.',
            'capacidad.min' => 'La capacidad debe ser al menos 1.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
        ]);

        Sala::create($validatedData);

        return redirect()->route('salas.index')->with('success', 'Sala creada exitosamente.');
    }

    /**
     * Muestra los detalles de una sala en formato JSON.
     */
    public function show(Sala $sala)
    {
        return response()->json($sala);
    }

    /**
     * Muestra el formulario para editar una sala.
     */
    public function edit(Sala $sala)
    {
        return view('actividades.salas.edit', compact('sala'));
    }

    /**
     * Actualiza una sala existente.
     */
    public function update(Request $request, Sala $sala)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'estado' => 'required|in:disponible,ocupada,mantenimiento',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'estado.required' => 'El campo estado es obligatorio.',
            'estado.in' => 'El estado debe ser disponible, ocupada o mantenimiento.',
            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.integer' => 'La capacidad debe ser un número entero.',
            'capacidad.min' => 'La capacidad debe ser al menos 1.',
            'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
        ]);

        $sala->update($validatedData);

        return redirect()->route('salas.index')->with('success', 'Sala actualizada exitosamente.');
    }

    /**
     * Elimina una sala.
     */
    public function destroy(Sala $sala)
    {
        $sala->delete();
        return redirect()->route('salas.index')->with('success', 'Sala eliminada exitosamente.');
    }
}
