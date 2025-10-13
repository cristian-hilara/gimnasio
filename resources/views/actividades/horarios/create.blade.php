@extends('layouts.template')
@section('title','Crear Horario')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .preview-card {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 1rem;
        border-radius: 8px;
        display: none;
    }
    .preview-card.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-calendar-plus"></i> Nuevo Horario de Actividad
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('actividad_horarios.index')}}">Horarios</a></li>
        <li class="breadcrumb-item active">Crear Horario</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus-circle"></i> Registrar Nuevo Horario
        </div>
        <div class="card-body">
            <form action="{{route('actividad_horarios.store')}}" method="POST">
                @csrf

                <!-- Vista previa de la actividad seleccionada -->
                <div id="actividadPreview" class="preview-card mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-dumbbell"></i> Actividad Seleccionada
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong id="preview_nombre"></strong>
                            <span id="preview_tipo" class="badge bg-info ms-2"></span>
                        </div>
                        <div class="col-md-6">
                            <small id="preview_descripcion" class="text-muted"></small>
                        </div>
                    </div>
                </div>

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
                                    data-nombre="{{$actividad->nombre}}"
                                    data-tipo="{{$actividad->tipoActividad->nombre}}"
                                    data-descripcion="{{$actividad->descripcion ?? 'Sin descripción'}}"
                                    {{ old('actividad_id') == $actividad->id ? 'selected' : '' }}>
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
                            <option value="lunes" {{ old('dia_semana') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                            <option value="martes" {{ old('dia_semana') == 'martes' ? 'selected' : '' }}>Martes</option>
                            <option value="miércoles" {{ old('dia_semana') == 'miércoles' ? 'selected' : '' }}>Miércoles</option>
                            <option value="jueves" {{ old('dia_semana') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                            <option value="viernes" {{ old('dia_semana') == 'viernes' ? 'selected' : '' }}>Viernes</option>
                            <option value="sábado" {{ old('dia_semana') == 'sábado' ? 'selected' : '' }}>Sábado</option>
                            <option value="domingo" {{ old('dia_semana') == 'domingo' ? 'selected' : '' }}>Domingo</option>
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
                            value="{{old('hora_inicio')}}"
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
                            value="{{old('hora_fin')}}"
                            required>
                        @error('hora_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Debe ser posterior a la hora de inicio</small>
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
                                <option value="{{$instructor->id}}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
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
                                <option value="{{$sala->id}}" {{ old('sala_id') == $sala->id ? 'selected' : '' }}>
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
                            value="{{old('cupo_maximo')}}"
                            placeholder="Ej: 20"
                            required>
                        @error('cupo_maximo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Número de personas permitidas</small>
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
                            <option value="1" {{ old('estado') == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('actividad_horarios.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Horario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Vista previa de actividad
    $('#actividad_id').on('change', function() {
        const selected = $(this).find('option:selected');
        
        if (selected.val()) {
            $('#preview_nombre').text(selected.data('nombre'));
            $('#preview_tipo').text(selected.data('tipo'));
            $('#preview_descripcion').text(selected.data('descripcion'));
            $('#actividadPreview').addClass('active');
        } else {
            $('#actividadPreview').removeClass('active');
        }
    });

    // Si hay valor antiguo, mostrar preview
    const oldValue = "{{ old('actividad_id') }}";
    if (oldValue) {
        $('#actividad_id').trigger('change');
    }
});
</script>
@endpush