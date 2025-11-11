@extends('layouts.templateCliente')

@section('title','Mi Perfil')

@section('content')
<div class="container mt-4">
    <h2>Mi Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $user->nombre }} {{ $user->apellido }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Teléfono:</strong> {{ $user->telefono ?? 'No registrado' }}</p>
            <p><strong>Peso:</strong> {{ $cliente->peso ?? 'No registrado' }} kg</p>
            <p><strong>Altura:</strong> {{ $cliente->altura ?? 'No registrado' }} m</p>
            <p><strong>Estado:</strong> {{ ucfirst($cliente->estado) }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('cliente.perfil.edit') }}" class="btn btn-primary">Editar Perfil</a>
        </div>
    </div>
</div>
@endsection
