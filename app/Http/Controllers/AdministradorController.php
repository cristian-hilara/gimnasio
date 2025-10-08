<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdministradorRequest;
use App\Http\Requests\UpdateAdministradorRequest;
use App\Models\Administrador;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function dashboard()
    {
        return view('panel.index');
    }

    public function index()
    {

        $admin = Administrador::all();

        return view('administradores.index', compact('admin'));
    }


    public function buscarUsuarios(Request $request)
    {
        $term = $request->get('term');
        $usuarios = \App\Models\Usuario::where('nombre', 'LIKE', '%' . $term . '%')
            ->orWhere('apellido', 'LIKE', '%' . $term . '%')
            ->select('id', 'nombre', 'apellido')
            ->get();

        // Puedes crear un campo completo para mostrar nombre y apellido juntos
        $data = $usuarios->map(function ($usuario) {
            return [
                'id' => $usuario->id,
                'label' => $usuario->nombre . ' ' . $usuario->apellido, // texto mostrado en la lista
                'value' => $usuario->nombre . ' ' . $usuario->apellido, // texto que queda en el input al seleccionar
            ];
        });

        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $usuario = Usuario::all();
        return view('administradores.create', compact('usuario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdministradorRequest $request)
    {
        // Validar si el usuario ya está registrado como administrador
        if (Administrador::where('usuario_id', $request->usuario_id)->exists()) {
            return redirect()->back()->withErrors(['usuario_id' => 'El usuario ya está registrado como administrador.'])->withInput();
        }
        //        Administrador::create($request->validated());
        DB::beginTransaction();
        try {
            //$administrador = new Administrador();
            //$administrador->usuario_id = $request->usuario_id;
            //$administrador->area_responsabilidad = $request->area_responsabilidad;
            //$administrador->save();
            Administrador::create($request->validated());

            DB::commit();
            return redirect()->route('administrador.index')->with('success', 'Administrador creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al crear el administrador: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Administrador::findOrFail($id);
        return view('administradores.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdministradorRequest $request, String $id)
    {
        try {
            DB::beginTransaction();

            $admin = Administrador::findOrFail($id);
            $admin->update($request->validated());

            DB::commit();
            return redirect()->route('administrador.index')->with('success', 'Administrador actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el administrador: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $admin = Administrador::findOrFail($id);
            $admin->delete();

            DB::commit();
            return redirect()->route('administrador.index')->with('success', 'Administrador eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al eliminar el administrador: ' . $e->getMessage()]);
        }
    }
}
