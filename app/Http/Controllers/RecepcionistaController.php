<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecepcionistaRequest;
use App\Http\Requests\UpdateRecepcionistaRequest;
use App\Models\Recepcionista;
use App\Models\Usuario;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Storage;

class RecepcionistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function dashboard()
    {

        if (!Auth::user()->hasRole('RECEPCIONISTA')) {
            abort(403, 'Acceso no autorizado');
        }

        $recepcionista = Recepcionista::where('usuario_id', Auth::user()->id)->first();

        if (!$recepcionista) {
            return redirect()->route('errors.recepcionista_no_registrado')
                ->with('warning', 'Aún no estás registrado como recepcionista.');
        }

        return view('panel.index', compact('recepcionista'));
    }

    public function index()
    {
        // Carga todos los recepcionistas con sus datos de usuario asociados

        $usuario = Usuario::all();
        $recepcionistas = Recepcionista::all();


        return view('recepcionistas.index', compact('recepcionistas', 'usuario'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // obterner solo usuarios con rol 'recepcionista' que aún no están registrados como recepcionistas
        $usuarios = Usuario::role('RECEPCIONISTA') // Filtra por rol usando Spatie
            ->whereNotIn('id', function ($query) {
                $query->select('usuario_id')->from('recepcionistas');
            })
            ->get();
        return view('recepcionistas.create', compact('usuarios'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRecepcionistaRequest  $request // Inyección del Form Request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecepcionistaRequest $request)
    {
        // La validación se realiza automáticamente por StoreRecepcionistaRequest

        try {
            DB::beginTransaction();

            


           Recepcionista::create([
                'usuario_id' => $request->usuario_id,
                'turno' => $request->turno,
                'estado' => $request->estado,
            ]);

            DB::commit();

            return redirect()->route('recepcionistas.index')
                ->with('success', 'Recepcionista registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Para debug: dd($e->getMessage()); 
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el recepcionista: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource. (Usado por el modal AJAX)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recepcionista = Recepcionista::with('usuario')->findOrFail($id);
        return response()->json($recepcionista);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Recepcionista $recepcionista)
    {
        $usuarios = Usuario::whereNotIn('id', function ($query) use ($recepcionista) {
            $query->select('usuario_id')
                ->from('recepcionistas')
                ->where('id', '!=', $recepcionista->id);
        })->get();

        return view('recepcionistas.edit', compact('recepcionista', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRecepcionistaRequest  $request // Inyección del Form Request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecepcionistaRequest $request,Recepcionista $recepcionista)
    {


        $recepcionista = Recepcionista::with('usuario')->findOrFail($recepcionista);


        try {
            DB::beginTransaction();


            $recepcionista->update([
                'turno' => $request->turno,
                'estado' => $request->estado,
            ]);


            DB::commit();

            return redirect()->route('recepcionistas.index')
                ->with('success', 'Recepcionista actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el recepcionista: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $recepcionista = Recepcionista::findOrFail($id);
            $recepcionista->delete();

            

            DB::commit();

            return response()->json(['message' => 'Recepcionista y usuario asociados eliminados correctamente.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'No se pudo eliminar el recepcionista.', 'error' => $e->getMessage()], 500);
        }
    }
}
