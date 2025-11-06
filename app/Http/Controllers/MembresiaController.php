<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use Illuminate\Http\Request;

class MembresiaController extends Controller
{
        public function index()
    {
        $membresias = Membresia::all();
        return view('membresias.membresias.index', compact('membresias'));
    }

    public function create()
    {
        return view('membresias.membresias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'duracion_dias' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'duracion_dias.required' => 'La duración es obligatoria',
            'duracion_dias.min' => 'La duración debe ser al menos 1 día',
            'precio.required' => 'El precio es obligatorio',
            'precio.min' => 'El precio no puede ser negativo'
        ]);

        try {
            Membresia::create($request->all());
            return redirect()->route('membresias.index')
                ->with('success', 'Membresía creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la membresía: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Membresia $membresia)
    {
        $membresia->load('promociones');
        return response()->json($membresia);
    }

    public function edit(Membresia $membresia)
    {
        return view('membresias.membresias.edit', compact('membresia'));
    }

    public function update(Request $request, Membresia $membresia)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'duracion_dias' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean'
        ]);

        try {
            $membresia->update($request->all());
            return redirect()->route('membresias.index')->with('success', 'Membresía actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Membresia $membresia)
    {
        try {
            $membresia->delete();
            return response()->json([
                'success' => true,
                'message' => 'Membresía eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}
