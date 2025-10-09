@extends('layouts.template')
@section('title','Crear Instructor')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .usuario-preview {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        display: none;
    }
    .usuario-preview.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .usuario-avatar {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 3px solid #667eea;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-chalkboard-teacher"></i> Nuevo Instructor
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('instructors.index')}}">Instructores</a></li>
        <li class="breadcrumb-item active">Crear Instructor</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-user-plus"></i> Registrar Nuevo Instructor
        </div>
        <div class="card-body">
            <form action="{{route('instructors.store')}}" method="POST">
                @csrf

                

            
                <div class="row">
                    <!-- Selección de Usuario -->
                    <div class="col-md-12 mb-4">
                        <label for="usuario_id" class="form-label">
                            <i class="fas fa-user"></i> Seleccionar Usuario *
                        </label>
                        <select name="usuario_id" id="usuario_id" 
                            class="form-select @error('usuario_id') is-invalid @enderror" >
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
                            Solo aparecen usuarios que aún no son instructores
                        </small>
                    </div>
                </div>

                <div class="row">
                    <!-- Especialidad -->
                    <div class="col-md-6 mb-3">
                        <label for="especialidad" class="form-label">
                            <i class="fas fa-medal"></i> Especialidad
                        </label>
                        <input type="text" 
                            name="especialidad" 
                            id="especialidad" 
                            class="form-control @error('especialidad') is-invalid @enderror"
                            value="{{old('especialidad')}}"
                            maxlength="100"
                            placeholder="Ej: Yoga, Crossfit, Musculación">
                        @error('especialidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Máximo 100 caracteres</small>
                    </div>

                    <!-- Experiencia -->
                    <div class="col-md-6 mb-3">
                        <label for="experiencia" class="form-label">
                            <i class="fas fa-clock"></i> Experiencia
                        </label>
                        <input type="text" 
                            name="experiencia" 
                            id="experiencia" 
                            class="form-control @error('experiencia') is-invalid @enderror"
                            value="{{old('experiencia')}}"
                            maxlength="100"
                            placeholder="Ej: 5 años, 2 años, Principiante">
                        @error('experiencia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Máximo 100 caracteres</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Estado -->
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">
                            <i class="fas fa-toggle-on"></i> Estado *
                        </label>
                        <select name="estado" id="estado" 
                            class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle"></i> Activo
                            </option>
                            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>
                                <i class="fas fa-times-circle"></i> Inactivo
                            </option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Espacio informativo -->
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-lightbulb"></i> 
                            <strong>Consejo:</strong> Completa la especialidad y experiencia para una mejor gestión.
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('instructors.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Instructor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

