@extends('layouts.template')

@section('title','Editar Ejercicio')

@section('content')
<div class="container mt-4">
    <h2>Editar Ejercicio</h2>

    <form action="{{ route('ejercicios.update',$ejercicio->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $ejercicio->nombre }}" required>
        </div>

        <div class="mb-3">
            <label>Grupo Muscular</label>
            <input type="text" name="grupo_muscular" class="form-control" value="{{ $ejercicio->grupo_muscular }}" required>
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control">{{ $ejercicio->descripcion }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('ejercicios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
