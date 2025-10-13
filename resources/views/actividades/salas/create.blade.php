@extends('layouts.template')
@section('title','Crear Sala')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-user-plus"></i> Crear Nueva Sala</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('salas.index') }}">Salas</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-custom">
            Formulario de Registro
        </div>
        <div class="card-body">
            <form action="{{ route('salas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf


                <h5 class="mb-3 text-primary"><i class="fas fa-briefcase"></i> Datos del Recepcionista</h5>
                <div class="row g-3">

                    <!--nombre de sala-->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" 
                        placeholder="ejm:sala maquina">
                        
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!--ubicacion-->
                    <div class="col-md-6">
                        <label for="ubicacion" class="form-label">Ubicación</label>
                        <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}" placeholder="ejm:piso 1">
                        @error('ubicacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--capacidad-->
                    <div class="col-md-6">
                        <label for="capacidad" class="form-label">Capacidad</label>
                        <input type="number" class="form-control @error('capacidad') is-invalid @enderror" id="capacidad" name="capacidad" value="{{ old('capacidad') }}" min="1" placeholder="ejm:20">
                        @error('capacidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!----estado-->
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" >
                            <option value="disponible" {{ old('estado') == 'disponible' ? 'selected' : '' }}>disponible</option>
                            <option value="ocupada" {{ old('estado') == 'ocupada' ? 'selected' : '' }}>ocupada</option>
                            <option value="mantenimiento" {{ old('estado') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            
                        </select>
                        @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Guardar Salas</button>
                    <a href="{{ route('salas.index') }}" class="btn btn-secondary btn-lg">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection