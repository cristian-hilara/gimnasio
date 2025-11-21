<?php

namespace App\Http\Controllers;

use App\Models\ActividadHorario;
use Illuminate\Http\Request;

class ClienteActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Actividades disponibles
        $actividades = ActividadHorario::with(['actividad', 'instructor.usuario', 'sala'])
            ->where('estado', true)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();
        
        return view('actividades.actividadcliente', compact('actividades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
