@extends('layouts.template')
@section('title','Editar Historial')

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
        <i class="fas fa-edit"></i> Editar Historial de Membresía
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('historial-membresias.index')}}">Historial Membresías</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-edit"></i> Actualizar Información
        </div>
        <div class="card-body">
            <!-- Información del registro -->
            <div class="alert alert-info">
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-user"></i> Cliente:</strong> 
                        {{$historialMembresia->cliente->usuario->nombre}} {{$historialMembresia->cliente->usuario->apellido}}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-id-card"></i> Membresía:</strong> 
                        {{$historialMembresia->membresia->nombre}}
                    </div>
                </div>
            </div>

            <form action="{{route('historial-membresias.update', $historialMembresia->id)}}" method="POST">
                @csrf
                @method('PUT')

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
                            value="{{old('fecha_inicio', $historialMembresia->fecha_inicio->format('Y-m-d'))}}"
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
                            value="{{old('fecha_fin', $historialMembresia->fecha_fin->format('Y-m-d'))}}"
                            required>
                        @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Estado -->
                    <div class="col-md-6 mb-3">
                        <label for="estado_membresia" class="form-label">
                            <i class="fas fa-toggle-on"></i> Estado *
                        </label>
                        <select name="estado_membresia" 
                            id="estado_membresia" 
                            class="form-select @error('estado_membresia') is-invalid @enderror" 
                            required>
                            <option value="vigente" 
                                {{ old('estado_membresia', $historialMembresia->estado_membresia) == 'vigente' ? 'selected' : '' }}>
                                Vigente
                            </option>
                            <option value="vencida" 
                                {{ old('estado_membresia', $historialMembresia->estado_membresia) == 'vencida' ? 'selected' : '' }}>
                                Vencida
                            </option>
                            <option value="suspendida" 
                                {{ old('estado_membresia', $historialMembresia->estado_membresia) == 'suspendida' ? 'selected' : '' }}>
                                Suspendida
                            </option>
                        </select>
                        @error('estado_membresia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Información de precios (solo lectura) -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-secondary">
                            <h6><i class="fas fa-dollar-sign"></i> Información de Pago (No editable)</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Precio Original:</strong> Bs. {{number_format($historialMembresia->precio_original, 2)}}
                                </div>
                                <div class="col-md-4">
                                    <strong>Descuento:</strong> Bs. {{number_format($historialMembresia->descuento_aplicado, 2)}}
                                </div>
                                <div class="col-md-4">
                                    <strong>Precio Final:</strong> Bs. {{number_format($historialMembresia->precio_final, 2)}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de fechas -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="alert alert-light">
                            <small>
                                <strong><i class="fas fa-calendar"></i> Registrado:</strong> 
                                {{$historialMembresia->created_at->format('d/m/Y H:i')}}
                                <br>
                                <strong><i class="fas fa-clock"></i> Última actualización:</strong> 
                                {{$historialMembresia->updated_at->format('d/m/Y H:i')}}
                            </small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('historial-membresias.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Actualizar
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