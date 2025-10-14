@extends('layouts.template')
@section('title','Editar Promoción')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-edit"></i> Editar Promoción
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('promociones.index')}}">Promociones</a></li>
        <li class="breadcrumb-item active">Editar Promoción</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-edit"></i> Actualizar Información de la Promoción
        </div>
        <div class="card-body">
            <!-- Información actual -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Promoción actual:</strong> {{$promocione->nombre}}
                        <span class="badge {{$promocione->estado_vigencia['clase']}}">
                            {{$promocione->estado_vigencia['texto']}}
                        </span>
                        <br>
                        <small>
                            Esta promoción tiene {{$promocione->membresias->count()}} membresía(s) asociada(s)
                        </small>
                    </div>
                </div>
            </div>

            <form action="{{route('promociones.update', $promocione->id)}}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">
                            <i class="fas fa-tag"></i> Nombre de la Promoción *
                        </label>
                        <input type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{old('nombre', $promocione->nombre)}}"
                            required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo" class="form-label">
                            <i class="fas fa-list"></i> Tipo de Promoción *
                        </label>
                        <select name="tipo" 
                            id="tipo" 
                            class="form-select @error('tipo') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione --</option>
                            <option value="precio_especial" 
                                {{ old('tipo', $promocione->tipo) == 'precio_especial' ? 'selected' : '' }}>
                                Precio Especial
                            </option>
                            <option value="descuento" 
                                {{ old('tipo', $promocione->tipo) == 'descuento' ? 'selected' : '' }}>
                                Descuento
                            </option>
                            <option value="dias_extra" 
                                {{ old('tipo', $promocione->tipo) == 'dias_extra' ? 'selected' : '' }}>
                                Días Extra
                            </option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Fecha Inicio -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Fecha de Inicio *
                        </label>
                        <input type="date" 
                            name="fecha_inicio" 
                            id="fecha_inicio" 
                            class="form-control @error('fecha_inicio') is-invalid @enderror"
                            value="{{old('fecha_inicio', $promocione->fecha_inicio->format('Y-m-d'))}}"
                            required>
                        @error('fecha_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fecha Fin -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_fin" class="form-label">
                            <i class="fas fa-calendar-check"></i> Fecha de Fin *
                        </label>
                        <input type="date" 
                            name="fecha_fin" 
                            id="fecha_fin" 
                            class="form-control @error('fecha_fin') is-invalid @enderror"
                            value="{{old('fecha_fin', $promocione->fecha_fin->format('Y-m-d'))}}"
                            required>
                        @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Estado -->
                    <div class="col-md-6 mb-3">
                        <label for="activa" class="form-label">
                            <i class="fas fa-toggle-on"></i> Estado *
                        </label>
                        <select name="activa" 
                            id="activa" 
                            class="form-select @error('activa') is-invalid @enderror" 
                            required>
                            <option value="1" {{ old('activa', $promocione->activa) == '1' ? 'selected' : '' }}>
                                Activa
                            </option>
                            <option value="0" {{ old('activa', $promocione->activa) == '0' ? 'selected' : '' }}>
                                Inactiva
                            </option>
                        </select>
                        @error('activa')
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
                            placeholder="Describe los detalles de la promoción...">{{old('descripcion', $promocione->descripcion)}}</textarea>
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
                                {{$promocione->created_at->format('d/m/Y H:i')}}
                                <br>
                                <strong><i class="fas fa-clock"></i> Última actualización:</strong> 
                                {{$promocione->updated_at->format('d/m/Y H:i')}}
                            </small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('promociones.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Actualizar Promoción
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
// Validar que fecha_fin sea mayor o igual a fecha_inicio
document.getElementById('fecha_inicio').addEventListener('change', function() {
    document.getElementById('fecha_fin').setAttribute('min', this.value);
});

// Establecer min en carga
document.getElementById('fecha_fin').setAttribute('min', document.getElementById('fecha_inicio').value);
</script>
@endpush