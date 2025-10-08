<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Usuario;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        if (!Auth::user()->hasRole('INSTRUCTOR')) {
            abort(403, 'Acceso no autorizado');
        }

        $instructor = Instructor::where('usuario_id', Auth::user()->id)->firstOrFail();

        return view('panel.panel_instructor', compact('instructor'));
        
    }

    public function index()
    {
        $instructores = Instructor::with('usuario')->get();
        return view('Instructores.index', compact('instructores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener usuarios que NO tienen su ID en la columna 'usuario_id' de la tabla 'instructors'
        $usuarios = Usuario::whereNotIn('id', function ($query) {
            // ASUMO que la columna de la clave foránea en la tabla 'instructors' es 'usuario_id'
            $query->select('usuario_id')->from('instructors');
        })->get();

        return view('Instructores.create', compact('usuarios'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // La validación del ID debe ser sobre la columna 'usuario_id'
            'usuario_id' => 'required|exists:usuarios,id|unique:instructors,usuario_id',
            'especialidad' => 'nullable|string|max:100',
            'experiencia' => 'nullable|string|max:100',
            'estado' => 'required|in:activo,inactivo'
        ], [
            'usuario_id.required' => 'Debe seleccionar un usuario',
            'usuario_id.unique' => 'Este usuario ya es instructor',
            // ... (otros mensajes)
        ]);

        try {
            DB::beginTransaction();

            $instructor = new Instructor();
            // ASIGNAR a la columna correcta: usuario_id
            $instructor->usuario_id = $request->usuario_id;
            $instructor->especialidad = $request->especialidad;
            $instructor->experiencia = $request->experiencia;
            $instructor->estado = $request->estado;

            $instructor->save();
            DB::commit();

            return redirect()->route('instructores.index')->with('success', 'Instructor registrado exitosamente');
        } catch (\Exception $e) {
            // ... (manejo de error)
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        $instructor->load('usuario');
        return response()->json($instructor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor)
    {
        // Obtener todos los usuarios que aún no son instructores, MÁS el usuario actual que sí lo es.
        $usuarios = Usuario::whereNotIn('id', function ($query) use ($instructor) {
            // Usar 'usuario_id'
            $query->select('usuario_id')
                ->from('instructors')
                // Ignorar el usuario_id actual del instructor que se edita
                ->where('usuario_id', '!=', $instructor->usuario_id);
        })->orWhere('id', $instructor->usuario_id)->get(); // Incluir el usuario actual

        return view('instructores.edit', compact('instructor', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instructor $instructor)
    {
        $request->validate([
            // La clave foránea es 'usuario_id'. Debe ser única, ignorando el usuario_id actual.
            'usuario_id' => [
                'required',
                'exists:usuarios,id',
                Rule::unique('instructors', 'usuario_id')->ignore($instructor->id), // Ignora por la PK del instructor (id de la tabla instructors)
            ],
            'especialidad' => 'nullable|string|max:100',
            'experiencia' => 'nullable|string|max:100',
            'estado' => 'required|in:activo,inactivo'
        ], [
            'usuario_id.required' => 'Debe seleccionar un usuario',
            'usuario_id.unique' => 'Este usuario ya es instructor',
            // ... (otros mensajes)
        ]);

        try {
            DB::beginTransaction();

            // ASIGNAR a la columna correcta: usuario_id
            $instructor->usuario_id = $request->usuario_id;
            $instructor->especialidad = $request->especialidad;
            $instructor->experiencia = $request->experiencia;
            $instructor->estado = $request->estado;

            $instructor->save();
            DB::commit();

            return redirect()->route('instructores.index')->with('success', 'Instructor actualizado exitosamente');
        } catch (\Exception $e) {
            // ... (manejo de error)
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        try {
            $instructor->delete();
            return response()->json([
                'success' => true,
                'message' => 'Instructor eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el instructor: ' . $e->getMessage()
            ], 500);
        }
    }
}
