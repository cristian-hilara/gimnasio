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

        $instructor = Instructor::where('usuario_id', Auth::user()->id)->first();

        if (!$instructor) {
            return redirect()->route('errors.instructor_no_registrado')
                ->with('warning', 'Tu perfil de instructor aún no está registrado.');
        }

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
        $usuarios = Usuario::role('INSTRUCTOR')
            ->whereNotIn('id', function ($query) {
                $query->select('usuario_id')->from('instructors');
            })
            ->get();

        return view('instructores.create', compact('usuarios'));
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
            'especialidad.string' => 'La especialidad debe ser una cadena de texto',
            'especialidad.max' => 'La especialidad no debe exceder los 100 caracteres',
            'experiencia.string' => 'La experiencia debe ser una cadena de texto',
            'experiencia.max' => 'La experiencia no debe exceder los 100 caracteres',
            'estado.required' => 'El estado es obligatorio'
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

            return redirect()->route('instructors.index')->with('success', 'Instructor registrado exitosamente');
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
        return view('instructores.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instructor $instructor)
    {
        $request->validate([
            
            'especialidad' => 'nullable|string|max:100',
            'experiencia' => 'nullable|string|max:100',
            'estado' => 'required|in:activo,inactivo'
        ], [
            'usuario_id.required' => 'Debe seleccionar un usuario',
            'usuario_id.unique' => 'Este usuario ya es instructor',
            'especialidad.string' => 'La especialidad debe ser una cadena de texto',
            'especialidad.max' => 'La especialidad no debe exceder los 100 caracteres',
            'experiencia.string' => 'La experiencia debe ser una cadena de texto',
            'experiencia.max' => 'La experiencia no debe exceder los 100 caracteres',
            'estado.required' => 'El estado es obligatorio'
        ]);

        try {
            DB::beginTransaction();
            $instructor->especialidad = $request->especialidad;
            $instructor->experiencia = $request->experiencia;
            $instructor->estado = $request->estado;

            $instructor->save();
            DB::commit();

            return redirect()->route('instructors.index')->with('success', 'Instructor actualizado exitosamente');
        } catch (\Exception $e) {
            
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el instructor: ' . $e->getMessage()]);
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
