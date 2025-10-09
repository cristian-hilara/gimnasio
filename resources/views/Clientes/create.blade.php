@extends('layouts.template')
@section('title','Crear Cliente')

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
    <h1 class="mt-4"><i class="fas fa-user-plus"></i> Crear Nuevo Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('clientes.index')}}">Clientes</a></li>
        <li class="breadcrumb-item active">Crear Cliente</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-user-plus"></i> Registrar Nuevo Cliente
        </div>
        <div class="card-body">
            <form action="{{route('clientes.store')}}" method="POST">
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
                            Solo aparecen usuarios que aún no son clientes
                        </small>
                    </div>
                </div>

                <div class="row">
                    <!-- Peso -->
                    <div class="col-md-6 mb-3">
                        <label for="peso" class="form-label">
                            <i class="fas fa-weight"></i> Peso (kg)
                        </label>
                        <input type="number" step="0.01" min="0" max="999.99" 
                            name="peso" id="peso" 
                            class="form-control @error('peso') is-invalid @enderror"
                            value="{{old('peso')}}"
                            placeholder="Ej: 75.50">
                        @error('peso')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Altura -->
                    <div class="col-md-6 mb-3">
                        <label for="altura" class="form-label">
                            <i class="fas fa-ruler-vertical"></i> Altura (m)
                        </label>
                        <input type="number" step="0.01" min="0" max="9.99" 
                            name="altura" id="altura" 
                            class="form-control @error('altura') is-invalid @enderror"
                            value="{{old('altura')}}"
                            placeholder="Ej: 1.75">
                        @error('altura')
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
                        <select name="estado" id="estado" 
                            class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>
                                Inactivo
                            </option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cálculo IMC (informativo) -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-calculator"></i> IMC (Calculado automáticamente)
                        </label>
                        <input type="text" id="imc_display" class="form-control" 
                            readonly placeholder="Ingrese peso y altura">
                        <small id="imc_clasificacion" class="form-text"></small>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('clientes.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Calcular IMC en tiempo real
    function calcularIMC() {
        const peso = parseFloat($('#peso').val());
        const altura = parseFloat($('#altura').val());
        
        if (peso && altura && altura > 0) {
            const imc = (peso / (altura * altura)).toFixed(2);
            $('#imc_display').val(imc);
            
            let clasificacion = '';
            let colorClass = '';
            
            if (imc < 18.5) {
                clasificacion = 'Bajo peso';
                colorClass = 'text-warning';
            } else if (imc < 25) {
                clasificacion = 'Normal';
                colorClass = 'text-success';
            } else if (imc < 30) {
                clasificacion = 'Sobrepeso';
                colorClass = 'text-info';
            } else {
                clasificacion = 'Obesidad';
                colorClass = 'text-danger';
            }
            
            $('#imc_clasificacion').html(`<strong class="${colorClass}">Clasificación: ${clasificacion}</strong>`);
        } else {
            $('#imc_display').val('');
            $('#imc_clasificacion').html('');
        }
    }
    
    // Escuchar cambios en peso y altura
    $('#peso, #altura').on('input', calcularIMC);
</script>
@endpush