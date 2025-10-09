@extends('layouts.template')
@section('title','Editar Recepcionista')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-edit"></i> Editar Recepcionista</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('recepcionistas.index') }}">Recepcionistas</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-custom">
            Edición de Recepcionista:
        </div>
        <div class="card-body">
            <form action="{{ route('recepcionistas.update', $recepcionista->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <h5 class="mb-3 text-primary"><i class="fas fa-briefcase"></i> Datos del Recepcionista</h5>


                <div class="col-md-12 mb-4 d-flex align-items-center gap-3">
                    <img src="{{ asset('storage/' . $recepcionista->usuario->foto) }}"
                        alt="Foto de {{ $recepcionista->usuario->nombre }}"
                        class="rounded-circle" width="50" height="50">
                    <div>
                        <label class="form-label">
                            <i class="fas fa-user"></i> Usuario asociado
                        </label>
                        <input type="text" class="form-control"
                            style="width: 200%; max-width: 600px;"
                            value="{{ $recepcionista->usuario->nombre }} {{ $recepcionista->usuario->apellido }} - {{ $recepcionista->usuario->email }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Campo de Turno -->
                    <div class="col-md-6">
                        <label for="turno" class="form-label">Turno</label>
                        <select class="form-select @error('turno') is-invalid @enderror" id="turno" name="turno" required>
                            <option value="">Seleccione el turno</option>
                            <option value="mañana" {{ old('turno', $recepcionista->turno) == 'mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="tarde" {{ old('turno', $recepcionista->turno) == 'tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="noche" {{ old('turno', $recepcionista->turno) == 'noche' ? 'selected' : '' }}>Noche</option>
                        </select>
                        @error('turno')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campo de Estado -->
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                            <option value="activo" {{ old('estado', $recepcionista->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $recepcionista->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-sync"></i> Actualizar Recepcionista</button>
                    <a href="{{ route('recepcionistas.index') }}" class="btn btn-secondary btn-lg">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection