@extends('layouts.template')
@section('title','Nueva Inscripción')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .precio-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        margin-top: 1rem;
        display: none;
    }
    .precio-card.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .precio-original {
        text-decoration: line-through;
        opacity: 0.7;
        font-size: 1.2rem;
    }
    .precio-final {
        font-size: 2.5rem;
        font-weight: bold;
    }
    .descuento-badge {
        font-size: 1.2rem;
        padding: 0.5rem 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-user-plus"></i> Nueva Inscripción
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('historial-membresias.index')}}">Historial Membresías</a></li>
        <li class="breadcrumb-item active">Nueva Inscripción</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar"></i> Registrar Compra de Membresía
        </div>
        <div class="card-body">
            <form action="{{route('inscripciones.store')}}" method="POST" id="formInscripcion">
                @csrf

                <div class="row">
                    <!-- Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="cliente_id" class="form-label">
                            <i class="fas fa-user"></i> Cliente *
                        </label>
                        <select name="cliente_id" 
                            id="cliente_id" 
                            class="form-select @error('cliente_id') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione un cliente --</option>
                            @foreach($clientes as $cliente)
                                <option value="{{$cliente->id}}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{$cliente->usuario->nombre}} {{$cliente->usuario->apellido}} - CI: {{$cliente->usuario->ci ?? 'N/A'}}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Membresía -->
                    <div class="col-md-6 mb-3">
                        <label for="membresia_id" class="form-label">
                            <i class="fas fa-id-card"></i> Membresía *
                        </label>
                        <select name="membresia_id" 
                            id="membresia_id" 
                            class="form-select @error('membresia_id') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione una membresía --</option>
                            @foreach($membresias as $membresia)
                                <option value="{{$membresia->id}}" {{ old('membresia_id') == $membresia->id ? 'selected' : '' }}>
                                    {{$membresia->nombre}} - {{$membresia->duracion_texto}} - Bs. {{number_format($membresia->precio, 2)}}
                                </option>
                            @endforeach
                        </select>
                        @error('membresia_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Card de Precio (se muestra al seleccionar membresía) -->
                <div class="precio-card" id="precioCard">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="mb-2">Precio Original</h5>
                            <div class="precio-original" id="precioOriginal">Bs. 0.00</div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div id="promocionInfo" style="display: none;">
                                <span class="badge descuento-badge bg-success">
                                    <i class="fas fa-tag"></i> <span id="descuentoPorcentaje">0</span>% OFF
                                </span>
                                <div class="mt-2">
                                    <small id="promocionNombre"></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <h5 class="mb-2">Precio Final</h5>
                            <div class="precio-final" id="precioFinal">Bs. 0.00</div>
                        </div>
                    </div>
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <div class="row">
                        <div class="col-md-6">
                            <small><i class="fas fa-calendar-alt"></i> Duración: <span id="duracionDias">0</span> días</small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small><i class="fas fa-calendar-check"></i> Vence: <span id="fechaFinEstimada">-</span></small>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Fecha de inicio (opcional) -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Fecha de Inicio
                        </label>
                        <input type="date" 
                            name="fecha_inicio" 
                            id="fecha_inicio" 
                            class="form-control @error('fecha_inicio') is-invalid @enderror"
                            value="{{old('fecha_inicio', date('Y-m-d'))}}">
                        @error('fecha_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Si no selecciona, se usará la fecha de hoy
                        </small>
                    </div>

                    <!-- Método de pago -->
                    <div class="col-md-6 mb-3">
                        <label for="metodo_pago" class="form-label">
                            <i class="fas fa-credit-card"></i> Método de Pago *
                        </label>
                        <select name="metodo_pago" 
                            id="metodo_pago" 
                            class="form-select @error('metodo_pago') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione --</option>
                            <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>
                                <i class="fas fa-money-bill-wave"></i> Efectivo
                            </option>
                            <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>
                                <i class="fas fa-credit-card"></i> Tarjeta
                            </option>
                            <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>
                                <i class="fas fa-exchange-alt"></i> Transferencia
                            </option>
                            <option value="qr" {{ old('metodo_pago') == 'qr' ? 'selected' : '' }}>
                                <i class="fas fa-qrcode"></i> QR
                            </option>
                        </select>
                        @error('metodo_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Referencia de pago -->
                    <div class="col-md-12 mb-3">
                        <label for="referencia_pago" class="form-label">
                            <i class="fas fa-hashtag"></i> Referencia de Pago
                        </label>
                        <input type="text" 
                            name="referencia_pago" 
                            id="referencia_pago" 
                            class="form-control @error('referencia_pago') is-invalid @enderror"
                            value="{{old('referencia_pago')}}"
                            placeholder="Número de comprobante, transacción, etc.">
                        @error('referencia_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('historial-membresias.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" id="btnSubmit" disabled>
                        <i class="fas fa-save"></i> Registrar Inscripción
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
    // Cargar precio al seleccionar membresía
    $('#membresia_id').on('change', function() {
        const membresiaId = $(this).val();
        
        if (!membresiaId) {
            $('#precioCard').removeClass('active');
            $('#btnSubmit').prop('disabled', true);
            return;
        }
        
        $.ajax({
            url: `/inscripciones/membresia/${membresiaId}/precio`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    
                    // Mostrar precios
                    $('#precioOriginal').text('Bs. ' + parseFloat(data.precio_original).toFixed(2));
                    $('#precioFinal').text('Bs. ' + parseFloat(data.precio_final).toFixed(2));
                    $('#duracionDias').text(data.duracion_dias);
                    $('#fechaFinEstimada').text(data.fecha_fin_estimada);
                    
                    // Mostrar info de promoción si existe
                    if (data.tiene_promocion) {
                        $('#descuentoPorcentaje').text(data.porcentaje_descuento.toFixed(0));
                        $('#promocionNombre').text(`Promoción: ${data.promocion.nombre} (válida hasta ${data.promocion.fecha_fin})`);
                        $('#promocionInfo').show();
                    } else {
                        $('#promocionInfo').hide();
                    }
                    
                    // Mostrar card
                    $('#precioCard').addClass('active');
                    $('#btnSubmit').prop('disabled', false);
                }
            },
            error: function() {
                alert('Error al cargar el precio de la membresía');
            }
        });
    });
    
    // Validar selección de cliente y membresía
    $('#cliente_id, #membresia_id').on('change', function() {
        const clienteId = $('#cliente_id').val();
        const membresiaId = $('#membresia_id').val();
        
        if (clienteId && membresiaId) {
            $('#btnSubmit').prop('disabled', false);
        } else {
            $('#btnSubmit').prop('disabled', true);
        }
    });
});
</script>
@endpush