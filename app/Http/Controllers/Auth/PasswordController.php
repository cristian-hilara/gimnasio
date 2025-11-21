<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Mostrar formulario de cambio de contraseña obligatorio
     */
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * Actualizar la contraseña del usuario autenticado
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|min:5|confirmed',
        ], [
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La nueva contraseña debe tener al menos 5 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->requiere_cambio_contrasena = false; // ya no requiere cambio
        $user->save();

        return redirect()->route('redirect')->with('success', 'Contraseña actualizada correctamente.');
    }
}
