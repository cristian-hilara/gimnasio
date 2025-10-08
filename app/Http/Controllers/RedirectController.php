<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }


        if ($user->hasRole('ADMINISTRADOR') || $user->hasRole('RECEPCIONISTA')) {
            return redirect()->route('dashboard');
        }

        if ($user->hasRole('INSTRUCTOR')) {
            return redirect()->route('instructor.panel');
        }

        if ($user->hasRole('CLIENTE')) {
            return redirect()->route('cliente.panel');
        }

        return redirect()->route('login')->withErrors(['email' => 'Rol no reconocido']);
    }
}
