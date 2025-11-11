@extends('layouts.template')

@section('title','Ejercicios')

@section('content')
<div class="container mt-4">
    <h2>Catálogo de Ejercicios</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('ejercicios.create') }}" class="btn btn-primary mb-3">Nuevo Ejercicio</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Grupo Muscular</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ejercicios as $ejercicio)
                <tr>
                    <td>{{ $ejercicio->nombre }}</td>
                    <td>{{ $ejercicio->grupo_muscular }}</td>
                    <td>{{ $ejercicio->descripcion }}</td>
                    <td>
                        <a href="{{ route('ejercicios.edit',$ejercicio->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('ejercicios.destroy',$ejercicio->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
