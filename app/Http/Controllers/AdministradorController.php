<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdministradorRequest;
use App\Http\Requests\UpdateAdministradorRequest;
use App\Models\Administrador;
use App\Models\Instructor;
use App\Models\Usuario;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function dashboard()
    {
        if (!Auth::user()->hasRole('ADMINISTRADOR')) {
            abort(403, 'Acceso no autorizado');
        }

        $administrador = Administrador::where('usuario_id', Auth::user()->id)->first();

        if (!$administrador) {
            return redirect()->route('errors.administrador_no_registrado')
                ->with('warning', 'Aún no estás registrado como administrador.');
        }

        return view('panel.index', compact('administrador'));
    }


    public function index()
    {

        $admin = Administrador::with('usuario')->get();
        return view('administradores.index', compact('admin'));
    }



    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {

        $usuarios = Usuario::role('ADMINISTRADOR') // Filtra por rol usando Spatie
            ->whereNotIn('id', function ($query) {
                $query->select('usuario_id')->from('administradors');
            })
            ->get();
        return view('administradores.create', compact('usuarios'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdministradorRequest $request)
    {
        DB::beginTransaction();
        try {
            //$administrador = new Administrador();
            //$administrador->usuario_id = $request->usuario_id;
            //$administrador->area_responsabilidad = $request->area_responsabilidad;
            //$administrador->save();
            Administrador::create($request->validated());

            DB::commit();
            return redirect()->route('administradors.index')->with('success', 'Administrador creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al crear el administrador: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Administrador $administrador)
    {
        $administrador->load('usuario');
        return response()->json($administrador);
    }

    /**
     * Show the form for editing the specified resource.
     */
    
    public function edit(Administrador $administrador)
    {
        return view('administradores.edit', compact('administrador'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdministradorRequest $request, Administrador $administrador)
    {
        try {
            DB::beginTransaction();

            $administrador->area_responsabilidad = $request->area_responsabilidad;
            $administrador->estado = $request->estado;
            $administrador->save();


            DB::commit();
            return redirect()->route('administradors.index')->with('success', 'Administrador actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el administrador: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrador $administrador)
    {
        try {
            DB::beginTransaction();

            $usuario = $administrador->usuario;

            // Verificar si el usuario está vinculado a otros módulos
            $estaVinculado = $usuario->cliente()->exists()
                || $usuario->recepcionista()->exists()
                || $usuario->instructor()->exists();

            if ($estaVinculado) {
                return response()->json([
                    'message' => 'No se puede eliminar este administrador porque el usuario está vinculado a otros módulos.'
                ], 403);
            }

            // Eliminar el registro de administrador
            $administrador->delete();

            DB::commit();

            return response()->json([
                'message' => 'Administrador eliminado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'No se pudo eliminar el administrador.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
