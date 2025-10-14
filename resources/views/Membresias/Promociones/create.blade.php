@extends('layouts.template')
@section('title','Crear Promoción')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-tags"></i> Nueva Promoción
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('promociones.index')}}">Promociones</a></li>
        <li class="breadcrumb-item active">Crear Promoción</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus-circle"></i> Registrar Nueva Promoción
        </div>
        <div class="card-body">
            <form action="{{route('promociones.store')}}" method="POST">
                @csrf

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
                            value="{{old('nombre')}}"
                            placeholder="Ej: Promo Verano 2025, Black Friday"
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
                            <option value="precio_especial" {{ old('tipo') == 'precio_especial' ? 'selected' : '' }}>
                                Precio Especial
                            </option>
                            <option value="descuento" {{ old('tipo') == 'descuento' ? 'selected' : '' }}>
                                Descuento
                            </option>
                            <option value="dias_extra" {{ old('tipo') == 'dias_extra' ? 'selected' : '' }}>
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
                            value="{{old('fecha_inicio')}}"
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
                            value="{{old('fecha_fin')}}"
                            required>
                        @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Debe ser igual o posterior a la fecha de inicio
                        </small>
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
                            <option value="1" {{ old('activa') == '1' ? 'selected' : '' }}>Activa</option>
                            <option value="0" {{ old('activa') == '0' ? 'selected' : '' }}>Inactiva</option>
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
                            placeholder="Describe los detalles de la promoción...">{{old('descripcion')}}</textarea>
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
                            <strong>Nota:</strong> Después de crear la promoción, podrás asociarle membresías 
                            con precios promocionales desde la lista de promociones.
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('promociones.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save"></i> Guardar Promoción
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
</script>
@endpush