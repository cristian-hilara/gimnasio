@extends('layouts.template')
@section('title','Editar Horario')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-edit"></i> Editar Horario
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('actividad_horarios.index')}}">Horarios</a></li>
        <li class="breadcrumb-item active">Editar Horario</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-edit"></i> Actualizar Horario de Actividad
        </div>
        <div class="card-body">
            <!-- Información actual del horario -->
            <div class="info-card">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-2">
                            <i class="fas fa-dumbbell"></i> {{$actividad_horario->actividad->nombre}}
                        </h5>
                        <p class="mb-1">
                            <span class="badge bg-light text-dark">{{$actividad_horario->actividad->tipoActividad->nombre}}</span>
                        </p>
                        <p class="mb-0">
                            <small>
                                <i class="fas fa-calendar-day"></i> {{ucfirst($actividad_horario->dia_semana)}} | 
                                <i class="fas fa-clock"></i> {{date('H:i', strtotime($actividad_horario->hora_inicio))}} - {{date('H:i', strtotime($actividad_horario->hora_fin))}}
                            </small>
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1">
                            <i class="fas fa-user-tie"></i> 
                            {{$actividad_horario->instructor->usuario->nombre}} {{$actividad_horario->instructor->usuario->apellido}}
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-door-open"></i> {{$actividad_horario->sala->nombre}}
                        </p>
                        <p class="mb-0">
                            <span class="badge {{$actividad_horario->estado ? 'bg-success' : 'bg-danger'}}">
                                {{$actividad_horario->estado ? 'Activo' : 'Inactivo'}}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{route('actividad_horarios.update', $actividad_horario->id)}}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Actividad -->
                    <div class="col-md-12 mb-4">
                        <label for="actividad_id" class="form-label">
                            <i class="fas fa-dumbbell"></i> Actividad *
                        </label>
                        <select name="actividad_id" 
                            id="actividad_id" 
                            class="form-select @error('actividad_id') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione una actividad --</option>
                            @foreach($actividades as $actividad)
                                <option value="{{$actividad->id}}" 
                                    {{ old('actividad_id', $actividad_horario->actividad_id) == $actividad->id ? 'selected' : '' }}>
                                    {{$actividad->nombre}} - {{$actividad->tipoActividad->nombre}}
                                </option>
                            @endforeach
                        </select>
                        @error('actividad_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Día de la semana -->
                    <div class="col-md-4 mb-3">
                        <label for="dia_semana" class="form-label">
                            <i class="fas fa-calendar-day"></i> Día de la Semana *
                        </label>
                        <select name="dia_semana" 
                            id="dia_semana" 
                            class="form-select @error('dia_semana') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione --</option>
                            <option value="lunes" {{ old('dia_semana', $actividad_horario->dia_semana) == 'lunes' ? 'selected' : '' }}>Lunes</option>
                            <option value="martes" {{ old('dia_semana', $actividad_horario->dia_semana) == 'martes' ? 'selected' : '' }}>Martes</option>
                            <option value="miércoles" {{ old('dia_semana', $actividad_horario->dia_semana) == 'miércoles' ? 'selected' : '' }}>Miércoles</option>
                            <option value="jueves" {{ old('dia_semana', $actividad_horario->dia_semana) == 'jueves' ? 'selected' : '' }}>Jueves</option>
                            <option value="viernes" {{ old('dia_semana', $actividad_horario->dia_semana) == 'viernes' ? 'selected' : '' }}>Viernes</option>
                            <option value="sábado" {{ old('dia_semana', $actividad_horario->dia_semana) == 'sábado' ? 'selected' : '' }}>Sábado</option>
                            <option value="domingo" {{ old('dia_semana', $actividad_horario->dia_semana) == 'domingo' ? 'selected' : '' }}>Domingo</option>
                        </select>
                        @error('dia_semana')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora inicio -->
                    <div class="col-md-4 mb-3">
                        <label for="hora_inicio" class="form-label">
                            <i class="fas fa-clock"></i> Hora de Inicio *
                        </label>
                        <input type="time" 
                            name="hora_inicio" 
                            id="hora_inicio" 
                            class="form-control @error('hora_inicio') is-invalid @enderror"
                            value="{{old('hora_inicio', date('H:i', strtotime($actividad_horario->hora_inicio)))}}"
                            required>
                        @error('hora_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora fin -->
                    <div class="col-md-4 mb-3">
                        <label for="hora_fin" class="form-label">
                            <i class="fas fa-clock"></i> Hora de Fin *
                        </label>
                        <input type="time" 
                            name="hora_fin" 
                            id="hora_fin" 
                            class="form-control @error('hora_fin') is-invalid @enderror"
                            value="{{old('hora_fin', date('H:i', strtotime($actividad_horario->hora_fin)))}}"
                            required>
                        @error('hora_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Instructor -->
                    <div class="col-md-4 mb-3">
                        <label for="instructor_id" class="form-label">
                            <i class="fas fa-user-tie"></i> Instructor *
                        </label>
                        <select name="instructor_id" 
                            id="instructor_id" 
                            class="form-select @error('instructor_id') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione un instructor --</option>
                            @foreach($instructores as $instructor)
                                <option value="{{$instructor->id}}" 
                                    {{ old('instructor_id', $actividad_horario->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                    {{$instructor->usuario->nombre}} {{$instructor->usuario->apellido}}
                                    @if($instructor->especialidad)
                                        - {{$instructor->especialidad}}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('instructor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sala -->
                    <div class="col-md-4 mb-3">
                        <label for="sala_id" class="form-label">
                            <i class="fas fa-door-open"></i> Sala *
                        </label>
                        <select name="sala_id" 
                            id="sala_id" 
                            class="form-select @error('sala_id') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione una sala --</option>
                            @foreach($salas as $sala)
                                <option value="{{$sala->id}}" 
                                    {{ old('sala_id', $actividad_horario->sala_id) == $sala->id ? 'selected' : '' }}>
                                    {{$sala->nombre}} (Cap: {{$sala->capacidad}})
                                </option>
                            @endforeach
                        </select>
                        @error('sala_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cupo máximo -->
                    <div class="col-md-4 mb-3">
                        <label for="cupo_maximo" class="form-label">
                            <i class="fas fa-users"></i> Cupo Máximo *
                        </label>
                        <input type="number" 
                            name="cupo_maximo" 
                            id="cupo_maximo" 
                            min="1"
                            class="form-control @error('cupo_maximo') is-invalid @enderror"
                            value="{{old('cupo_maximo', $actividad_horario->cupo_maximo)}}"
                            required>
                        @error('cupo_maximo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Estado -->
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">
                            <i class="fas fa-toggle-on"></i> Estado *
                        </label>
                        <select name="estado" 
                            id="estado" 
                            class="form-select @error('estado') is-invalid @enderror" 
                            required>
                            <option value="1" {{ old('estado', $actividad_horario->estado) == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estado', $actividad_horario->estado) == '0' ? 'selected' : '' }}>Inactivo</option>
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
                                <strong>Registrado:</strong> {{$actividad_horario->created_at->format('d/m/Y H:i')}}
                                <br>
                                <strong>Última actualización:</strong> {{$actividad_horario->updated_at->format('d/m/Y H:i')}}
                            </small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('actividad_horarios.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Horario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection