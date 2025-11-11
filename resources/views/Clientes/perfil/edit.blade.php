@extends('layouts.templateCliente')

@section('title','Editar Perfil')

@section('content')
<div class="container mt-4">
    <h2>Editar Perfil</h2>

    <form action="{{ route('cliente.perfil.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $user->nombre) }}">
        </div>

        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $user->apellido) }}">
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}">
        </div>

        <div class="mb-3">
            <label for="peso" class="form-label">Peso (kg)</label>
            <input type="number" step="0.01" name="peso" class="form-control" value="{{ old('peso', $cliente->peso) }}">
        </div>

        <div class="mb-3">
            <label for="altura" class="form-label">Altura (m)</label>
            <input type="number" step="0.01" name="altura" class="form-control" value="{{ old('altura', $cliente->altura) }}">
        </div>

        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="{{ route('cliente.perfil') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
