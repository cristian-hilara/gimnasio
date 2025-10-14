<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\Promocion;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
       public function index()
    {
        $promociones = Promocion::with('membresias')->get();
        $membresias = Membresia::where('estado', true)->get();
        return view('membresias.promociones.index', compact('promociones', 'membresias'));
    }

    public function create()
    {
        return view('membresias.promociones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:precio_especial,descuento,dias_extra',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'activa' => 'required|boolean'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'tipo.required' => 'Debe seleccionar un tipo',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_fin.required' => 'La fecha de fin es obligatoria',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio'
        ]);

        try {
            Promocion::create($request->all());
            return redirect()->route('promociones.index')
                ->with('success', 'Promoción creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la promoción: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Promocion $promocione)
    {
        $promocione->load('membresias');
        return response()->json($promocione);
    }

    public function edit(Promocion $promocione)
    {
        return view('membresias.promociones.edit', compact('promocione'));
    }

    public function update(Request $request, Promocion $promocione)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:precio_especial,descuento,dias_extra',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'activa' => 'required|boolean'
        ]);

        try {
            $promocione->update($request->all());
            return redirect()->route('promociones.index')
                ->with('success', 'Promoción actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Promocion $promocione)
    {
        try {
            $promocione->delete();
            return response()->json([
                'success' => true,
                'message' => 'Promoción eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}
