<?php

namespace App\Http\Controllers;

use App\Models\Ejercicio;
use Illuminate\Http\Request;

class EjercicioController extends Controller
{
    public function index()
    {
        $ejercicios = Ejercicio::all();
        return view('clientes.ejercicios.index', compact('ejercicios'));
    }

    public function create()
    {
        return view('clientes.ejercicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'grupo_muscular' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        Ejercicio::create($request->all());

        return redirect()->route('ejercicios.index')->with('success', 'Ejercicio creado correctamente');
    }

    public function edit(Ejercicio $ejercicio)
    {
        return view('clientes.ejercicios.edit', compact('ejercicio'));
    }

    public function update(Request $request, Ejercicio $ejercicio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'grupo_muscular' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $ejercicio->update($request->all());

        return redirect()->route('ejercicios.index')->with('success', 'Ejercicio actualizado correctamente');
    }

    public function destroy(Ejercicio $ejercicio)
    {
        $ejercicio->delete();
        return redirect()->route('ejercicios.index')->with('success', 'Ejercicio eliminado correctamente');
    }
}
