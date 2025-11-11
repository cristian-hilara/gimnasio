@extends('layouts.template')

@section('title','Nuevo Ejercicio')

@section('content')
<div class="container mt-4">
    <h2>Nuevo Ejercicio</h2>

    <form action="{{ route('ejercicios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Grupo Muscular</label>
            <input type="text" name="grupo_muscular" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('ejercicios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
