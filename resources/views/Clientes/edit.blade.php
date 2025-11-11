@extends('layouts.template')
@section('title','Editar Cliente')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }

    .card-header {
        background: linear-gradient(135deg, #246ddbff 0%, #3e05e9ff 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-user-edit"></i> Editar Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('clientes.index')}}">Clientes</a></li>
        <li class="breadcrumb-item active">Editar Cliente</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-edit"></i> Actualizar Información del Cliente
        </div>
        <div class="card-body">
            <form action="{{route('clientes.update')}}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Información del Usuario Actual -->
                    <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Usuario actual:</strong>
                            {{$cliente->usuario->nombre}} {{$cliente->usuario->apellido}}
                            ({{$cliente->usuario->email}})
                        </div>
                    </div>

                    <!-- Usuario asociado (con foto) -->
                    <div class="col-md-12 mb-4 d-flex align-items-center gap-3">
                        <img src="{{ asset('storage/' . $cliente->usuario->foto) }}"
                            alt="Foto de {{ $cliente->usuario->nombre }}"
                            class="rounded-circle" width="50" height="50">
                        <div class="flex-grow-1">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Usuario asociado
                            </label>
                            <input type="text" class="form-control"
                                style="max-width: 600px;"
                                value="{{ $cliente->usuario->nombre }} {{ $cliente->usuario->apellido }} - {{ $cliente->usuario->email }}"
                                readonly>
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
                                value="{{old('peso', $cliente->peso)}}"
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
                                value="{{old('altura', $cliente->altura)}}"
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
                                <option value="activo"
                                    {{ old('estado', $cliente->estado) == 'activo' ? 'selected' : '' }}>
                                    Activo
                                </option>
                                <option value="inactivo"
                                    {{ old('estado', $cliente->estado) == 'inactivo' ? 'selected' : '' }}>
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

                    <!-- Información adicional -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-secondary">
                                <strong><i class="fas fa-qrcode"></i> Código QR:</strong>
                                <code>{{$cliente->codigoQR}}</code>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> Registrado: {{$cliente->created_at->format('d/m/Y H:i')}}
                                </small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{route('clientes.index')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Cliente
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

    // Calcular al cargar la página
    $(document).ready(function() {
        calcularIMC();
    });

    // Escuchar cambios en peso y altura
    $('#peso, #altura').on('input', calcularIMC);
</script>
@endpush