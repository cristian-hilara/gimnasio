<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Auth;
use Illuminate\Http\Request;

class ClientePerfilController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $cliente = $user->cliente()->with('usuario')->firstOrFail();

        return view('clientes.perfil.show', compact('user', 'cliente'));
    }

    public function edit()
    {
        $user = Auth::user();
        $cliente = $user->cliente()->with('usuario')->firstOrFail();

        return view('clientes.perfil.edit', compact('user', 'cliente'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $cliente = $user->cliente()->with('usuario')->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'peso' => 'nullable|numeric',
            'altura' => 'nullable|numeric',
        ]);

        // actualizar usuario
        $user->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
        ]);

        // actualizar cliente
        $cliente->update([
            'peso' => $request->peso,
            'altura' => $request->altura,
        ]);

        
        return redirect()->route('cliente.perfil')->with('success', 'Perfil actualizado correctamente');

    }
}
