@extends('layouts.template')
@section('title','Editar Pago')

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
        <i class="fas fa-edit"></i> Editar Pago
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('pagos.index')}}">Pagos</a></li>
        <li class="breadcrumb-item active">Editar Pago</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-edit"></i> Actualizar Información del Pago
        </div>
        <div class="card-body">
            <!-- Información del pago actual -->
            <div class="alert alert-info">
                <div class="row">
                    <div class="col-md-4">
                        <strong><i class="fas fa-user"></i> Cliente:</strong> 
                        {{$pago->cliente->usuario->nombre}} {{$pago->cliente->usuario->apellido}}
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-id-card"></i> Membresía:</strong> 
                        {{$pago->historialMembresia->membresia->nombre}}
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-receipt"></i> ID Pago:</strong> 
                        #{{$pago->id}}
                    </div>
                </div>
            </div>

            <form action="{{route('pagos.update', $pago->id)}}" method="POST">
                @csrf
                @method('PUT')

                <!-- Campos ocultos (no editables) -->
                <input type="hidden" name="cliente_id" value="{{$pago->cliente_id}}">
                <input type="hidden" name="historial_membresia_id" value="{{$pago->historial_membresia_id}}">

                <div class="row">
                    <!-- Fecha de Pago -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_pago" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Fecha de Pago *
                        </label>
                        <input type="date" 
                            name="fecha_pago" 
                            id="fecha_pago" 
                            class="form-control @error('fecha_pago') is-invalid @enderror"
                            value="{{old('fecha_pago', $pago->fecha_pago->format('Y-m-d'))}}"
                            required>
                        @error('fecha_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Monto -->
                    <div class="col-md-6 mb-3">
                        <label for="monto" class="form-label">
                            <i class="fas fa-dollar-sign"></i> Monto (Bs.) *
                        </label>
                        <input type="number" 
                            name="monto" 
                            id="monto" 
                            step="0.01"
                            min="0"
                            class="form-control @error('monto') is-invalid @enderror"
                            value="{{old('monto', $pago->monto)}}"
                            required>
                        @error('monto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Método de Pago -->
                    <div class="col-md-6 mb-3">
                        <label for="metodo_pago" class="form-label">
                            <i class="fas fa-credit-card"></i> Método de Pago *
                        </label>
                        <select name="metodo_pago" 
                            id="metodo_pago" 
                            class="form-select @error('metodo_pago') is-invalid @enderror" 
                            required>
                            <option value="">-- Seleccione --</option>
                            <option value="efectivo" {{ old('metodo_pago', $pago->metodo_pago) == 'efectivo' ? 'selected' : '' }}>
                                💵 Efectivo
                            </option>
                            <option value="tarjeta" {{ old('metodo_pago', $pago->metodo_pago) == 'tarjeta' ? 'selected' : '' }}>
                                💳 Tarjeta
                            </option>
                            <option value="transferencia" {{ old('metodo_pago', $pago->metodo_pago) == 'transferencia' ? 'selected' : '' }}>
                                🏦 Transferencia
                            </option>
                            <option value="qr" {{ old('metodo_pago', $pago->metodo_pago) == 'qr' ? 'selected' : '' }}>
                                📱 QR
                            </option>
                        </select>
                        @error('metodo_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Referencia de Pago -->
                    <div class="col-md-6 mb-3">
                        <label for="referencia_pago" class="form-label">
                            <i class="fas fa-hashtag"></i> Referencia de Pago
                        </label>
                        <input type="text" 
                            name="referencia_pago" 
                            id="referencia_pago" 
                            class="form-control @error('referencia_pago') is-invalid @enderror"
                            value="{{old('referencia_pago', $pago->referencia_pago)}}"
                            placeholder="Número de transacción, comprobante, etc."
                            maxlength="255">
                        @error('referencia_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Información de la membresía asociada -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-secondary">
                            <h6><i class="fas fa-id-card"></i> Membresía Asociada</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Membresía:</strong> {{$pago->historialMembresia->membresia->nombre}}
                                </div>
                                <div class="col-md-4">
                                    <strong>Vigencia:</strong> 
                                    {{$pago->historialMembresia->fecha_inicio->format('d/m/Y')}} - 
                                    {{$pago->historialMembresia->fecha_fin->format('d/m/Y')}}
                                </div>
                                <div class="col-md-4">
                                    <strong>Estado:</strong> 
                                    <span class="badge {{$pago->historialMembresia->estado_badge['clase']}}">
                                        {{$pago->historialMembresia->estado_badge['texto']}}
                                    </span>
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
                                {{$pago->created_at->format('d/m/Y H:i')}}
                                <br>
                                <strong><i class="fas fa-clock"></i> Última actualización:</strong> 
                                {{$pago->updated_at->format('d/m/Y H:i')}}
                            </small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('pagos.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Actualizar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection