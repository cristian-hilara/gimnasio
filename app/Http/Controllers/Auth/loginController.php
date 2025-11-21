<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; ////////////
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; ////////////

class loginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('redirect');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            //Verificar si requiere cambio de contraseña
            if ($user->requiere_cambio_contrasena) {
                return redirect()->route('password.change.form')
                    ->with('warning', 'Debes cambiar tu contraseña antes de continuar.');
            }

            if ($user->hasRole('ADMINISTRADOR') || $user->hasRole('RECEPCIONISTA')) {
                return redirect()->route('dashboard')->with('success', '¡Bienvenido ' . $user->nombre . '!');
            }

            if ($user->hasRole('INSTRUCTOR')) {
                return redirect()->route('instructor.panel')->with('success', '¡Bienvenido ' . $user->nombre . '!');
            }

            if ($user->hasRole('CLIENTE')) {
                return redirect()->route('cliente.panel')->with('success', '¡Bienvenido ' . $user->nombre . '!');
            }

            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Tu cuenta no tiene un rol válido']);
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }
}
