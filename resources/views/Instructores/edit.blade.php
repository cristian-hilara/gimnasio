@extends('layouts.template')
@section('title','Editar Instructor')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }

    .card-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .usuario-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .usuario-avatar-large {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        <i class="fas fa-edit"></i> Editar Instructor
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('instructors.index')}}">Instructores</a></li>
        <li class="breadcrumb-item active">Editar Instructor</li>
    </ol>

    <!-- Card de información actual del instructor -->
    <div class="usuario-info-card shadow-lg">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ $instructor->usuario->foto ? asset('storage/'.$instructor->usuario->foto) : asset('img/default-avatar.png') }}"
                    alt="Foto de {{ $instructor->usuario->nombre }}"
                    class="rounded-circle usuario-avatar-large">
            </div>
            <div class="col">
                <h3 class="mb-1">
                    <i class="fas fa-user-circle"></i>
                    {{ $instructor->usuario->nombre }} {{ $instructor->usuario->apellido }}
                </h3>
                <p class="mb-1">
                    <i class="fas fa-envelope"></i> {{ $instructor->usuario->email }}
                </p>
                <p class="mb-0">
                    <i class="fas fa-phone"></i> {{ $instructor->usuario->telefono ?? 'No especificado' }}
                </p>
            </div>
            <div class="col-auto">
                <span class="badge {{ $instructor->estado == 'activo' ? 'bg-success' : 'bg-danger' }} fs-6">
                    {{ $instructor->estado == 'activo' ? 'ACTIVO' : 'INACTIVO' }}
                </span>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-edit"></i> Actualizar Información del Instructor
        </div>
        <div class="card-body">
            <form action="{{route('instructors.update', $instructor->id)}}" method="POST">
                @csrf
                @method('PUT')

    

                 <h5 class="mb-3 text-primary"><i class="fas fa-briefcase"></i> Datos del Instructor</h5>

                <div class="col-md-12 mb-4 d-flex align-items-center gap-3">
                    <img src="{{ asset('storage/' . $instructor->usuario->foto) }}"
                        alt="Foto de {{ $instructor->usuario->nombre }}"
                        class="rounded-circle" width="50" height="50">
                    <div>
                        <label class="form-label">
                            <i class="fas fa-user"></i> Usuario asociado
                        </label>
                        <input type="text" class="form-control"
                            style="width: 200%; max-width: 600px;"
                            value="{{ $instructor->usuario->nombre }} {{ $instructor->usuario->apellido }} - {{ $instructor->usuario->email }}"
                            readonly>
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
                            value="{{old('especialidad', $instructor->especialidad)}}"
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
                            value="{{old('experiencia', $instructor->experiencia)}}"
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
                            <option value="activo"
                                {{ old('estado', $instructor->estado) == 'activo' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle"></i> Activo
                            </option>
                            <option value="inactivo"
                                {{ old('estado', $instructor->estado) == 'inactivo' ? 'selected' : '' }}>
                                <i class="fas fa-times-circle"></i> Inactivo
                            </option>
                        </select>
                        @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Información de fechas -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-calendar"></i> Información de Registro
                        </label>
                        <div class="alert alert-secondary mb-0">
                            <small>
                                <strong>Registrado:</strong> {{$instructor->created_at->format('d/m/Y H:i')}}
                                <br>
                                <strong>Última actualización:</strong> {{$instructor->updated_at->format('d/m/Y H:i')}}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas adicionales -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información adicional:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Este instructor está registrado hace {{ $instructor->created_at->diffForHumans() }}</li>
                                @if($instructor->especialidad)
                                <li>Especialidad actual: <strong>{{ $instructor->especialidad }}</strong></li>
                                @endif
                                @if($instructor->experiencia)
                                <li>Experiencia: <strong>{{ $instructor->experiencia }}</strong></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('instructors.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Instructor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush