@extends('layouts.template')
@section('title','Crear Actividad')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-dumbbell"></i> Nueva Actividad
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('actividades.index')}}">Actividades</a></li>
        <li class="breadcrumb-item active">Crear Actividad</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus-circle"></i> Registrar Nueva Actividad
        </div>
        <div class="card-body">
            <form action="{{route('actividades.store')}}" method="POST">
                @csrf

                <div class="row">
                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">
                            <i class="fas fa-tag"></i> Nombre de la Actividad *
                        </label>
                        <input type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{old('nombre')}}"
                            placeholder="Ej: Spinning Avanzado, Yoga Matutino"
                            required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tipo de Actividad -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo_actividad_id" class="form-label">
                            <i class="fas fa-list"></i> Tipo de Actividad *
                        </label>
                        <select name="tipo_actividad_id" 
                            id="tipo_actividad_id" 
                            class="form-select @error('tipo_actividad_id') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione un tipo --</option>
                            @foreach($tiposActividad as $tipo)
                                <option value="{{$tipo->id}}" {{ old('tipo_actividad_id') == $tipo->id ? 'selected' : '' }}>
                                    {{$tipo->nombre}}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_actividad_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Si no encuentras el tipo, puedes agregarlo desde 
                            <a href="{{route('actividades.index')}}" target="_blank">Gestionar Tipos</a>
                        </small>
                    </div>
                </div>

                <div class="row">
                    <!-- Descripción -->
                    <div class="col-md-12 mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left"></i> Descripción
                        </label>
                        <textarea 
                            name="descripcion" 
                            id="descripcion" 
                            rows="4"
                            class="form-control @error('descripcion') is-invalid @enderror"
                            placeholder="Describe los detalles de la actividad, nivel requerido, beneficios, etc.">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb"></i> 
                            <strong>Nota:</strong> Después de crear la actividad, podrás asignarle horarios desde el módulo de 
                            <a href="{{route('actividad_horarios.index')}}" class="alert-link">Horarios de Actividades</a>.
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('actividades.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Actividad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection