@extends('layouts.template')
@section('title','Editar Actividad')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-edit"></i> Editar Actividad
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('actividades.index')}}">Actividades</a></li>
        <li class="breadcrumb-item active">Editar Actividad</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-edit"></i> Actualizar Información de la Actividad
        </div>
        <div class="card-body">


            <form action="{{route('actividades.update', $actividade->id)}}" method="POST">
                @csrf
                @method('PUT')

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
                            value="{{old('nombre', $actividade->nombre)}}"
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
                                <option value="{{$tipo->id}}" 
                                    {{ old('tipo_actividad_id', $actividade->tipo_actividad_id) == $tipo->id ? 'selected' : '' }}>
                                    {{$tipo->nombre}}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_actividad_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            placeholder="Describe los detalles de la actividad">{{old('descripcion', $actividade->descripcion)}}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Información de fechas -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="alert alert-secondary">
                            <small>
                                <strong><i class="fas fa-calendar"></i> Registrado:</strong> 
                                {{$actividade->created_at->format('d/m/Y H:i')}}
                                <br>
                                <strong><i class="fas fa-clock"></i> Última actualización:</strong> 
                                {{$actividade->updated_at->format('d/m/Y H:i')}}
                            </small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('actividades.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Actividad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection