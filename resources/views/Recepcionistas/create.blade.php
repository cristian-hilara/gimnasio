@extends('layouts.template')
@section('title','Crear Recepcionista')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-user-plus"></i> Crear Nuevo Recepcionista</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('recepcionistas.index') }}">Recepcionistas</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-custom">
            Formulario de Registro
        </div>
        <div class="card-body">
            <form action="{{ route('recepcionistas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf


                <h5 class="mb-3 text-primary"><i class="fas fa-briefcase"></i> Datos del Recepcionista</h5>
                <div class="row g-3">

                    <!-- Selección de Usuario -->
                    <div class="row">
                        <!-- Selección de Usuario -->
                        <div class="col-md-12 mb-4">
                            <label for="usuario_id" class="form-label">
                                <i class="fas fa-user"></i> Seleccionar Usuario *
                            </label>
                            <select name="usuario_id" id="usuario_id"
                                class="form-select @error('usuario_id') is-invalid @enderror">
                                <option value="">-- Seleccione un usuario --</option>
                                @foreach($usuarios as $usuario)
                                <option value="{{$usuario->id}}"
                                    {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                    {{$usuario->nombre}} {{$usuario->apellido}} - {{$usuario->email}}
                                </option>
                                @endforeach
                            </select>
                            @error('usuario_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Solo aparecen usuarios que aún no son recepcionistas
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="turno" class="form-label">Turno</label>
                        <select class="form-select @error('turno') is-invalid @enderror" id="turno" name="turno" required>
                            <option value="">Seleccione el turno</option>
                            <option value="mañana" {{ old('turno') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="tarde" {{ old('turno') == 'tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="noche" {{ old('turno') == 'noche' ? 'selected' : '' }}>Noche</option>
                        </select>
                        @error('turno')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Guardar Recepcionista</button>
                    <a href="{{ route('recepcionistas.index') }}" class="btn btn-secondary btn-lg">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection