@extends('layouts.template')
@section('title','Registrar Pago')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    .historial-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        display: none;
    }
    .historial-card.active {
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
        <i class="fas fa-receipt"></i> Registrar Pago
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('pagos.index')}}">Pagos</a></li>
        <li class="breadcrumb-item active">Registrar Pago</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus-circle"></i> Nuevo Registro de Pago
        </div>
        <div class="card-body">
            <form action="{{route('pagos.store')}}" method="POST">
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
                                    {{$cliente->usuario->nombre}} {{$cliente->usuario->apellido}} - {{$cliente->usuario->email}}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Historial de Membresía -->
                    <div class="col-md-6 mb-3">
                        <label for="historial_membresia_id" class="form-label">
                            <i class="fas fa-id-card"></i> Membresía *
                        </label>
                        <select name="historial_membresia_id" 
                            id="historial_membresia_id" 
                            class="form-select @error('historial_membresia_id') is-invalid @enderror" 
                            required
                            disabled>
                            <option value="">-- Primero seleccione un cliente --</option>
                        </select>
                        @error('historial_membresia_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Vista previa del historial seleccionado -->
                <div class="historial-card" id="historialCard">
                    <div class="row">
                        <div class="col-md-4">
                            <strong><i class="fas fa-id-card"></i> Membresía:</strong>
                            <div id="preview_membresia">-</div>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-calendar"></i> Vigencia:</strong>
                            <div id="preview_vigencia">-</div>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-toggle-on"></i> Estado:</strong>
                            <div id="preview_estado">-</div>
                        </div>
                    </div>
                </div>

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
                            value="{{old('fecha_pago', date('Y-m-d'))}}"
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
                            value="{{old('monto')}}"
                            placeholder="Ej: 150.00"
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
                            <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>
                                 Efectivo
                            </option>
                            <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>
                                 Tarjeta
                            </option>
                            <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>
                                 Transferencia
                            </option>
                            <option value="qr" {{ old('metodo_pago') == 'qr' ? 'selected' : '' }}>
                                 QR
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
                            value="{{old('referencia_pago')}}"
                            placeholder="Número de transacción, comprobante, etc."
                            maxlength="255">
                        @error('referencia_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Opcional: Ingrese número de comprobante o referencia
                        </small>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Nota:</strong> El pago se asociará a la membresía seleccionada del cliente.
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('pagos.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar Pago
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
    // Cargar historiales al seleccionar cliente
    $('#cliente_id').on('change', function() {
        const clienteId = $(this).val();
        
        if (!clienteId) {
            $('#historial_membresia_id').html('<option value="">-- Primero seleccione un cliente --</option>');
            $('#historial_membresia_id').prop('disabled', true);
            $('#historialCard').removeClass('active');
            return;
        }
        
        // Cargar historiales del cliente
        $.ajax({
            url: `/pagos/cliente/${clienteId}/historiales`,
            method: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let options = '<option value="">-- Seleccione una membresía --</option>';
                    response.data.forEach(historial => {
                        const estadoBadge = historial.estado_membresia === 'vigente' ? '✅' : 
                                          historial.estado_membresia === 'vencida' ? '❌' : '⏸️';
                        options += `<option value="${historial.id}" 
                                        data-membresia="${historial.membresia.nombre}"
                                        data-inicio="${historial.fecha_inicio}"
                                        data-fin="${historial.fecha_fin}"
                                        data-estado="${historial.estado_membresia}"
                                        data-precio="${historial.precio_final}">
                            ${estadoBadge} ${historial.membresia.nombre} - ${historial.fecha_inicio} al ${historial.fecha_fin}
                        </option>`;
                    });
                    $('#historial_membresia_id').html(options);
                    $('#historial_membresia_id').prop('disabled', false);
                } else {
                    $('#historial_membresia_id').html('<option value="">No hay membresías vigentes</option>');
                    $('#historial_membresia_id').prop('disabled', true);
                    alert('Este cliente no tiene membresías vigentes');
                }
            },
            error: function() {
                alert('Error al cargar las membresías del cliente');
            }
        });
    });
    
    // Mostrar vista previa del historial seleccionado
    $('#historial_membresia_id').on('change', function() {
        const selected = $(this).find('option:selected');
        
        if (selected.val()) {
            const membresia = selected.data('membresia');
            const inicio = selected.data('inicio');
            const fin = selected.data('fin');
            const estado = selected.data('estado');
            const precio = parseFloat(selected.data('precio'));
            
            const estadoBadge = estado === 'vigente' ? '<span class="badge bg-success">Vigente</span>' : 
                              estado === 'vencida' ? '<span class="badge bg-danger">Vencida</span>' : 
                              '<span class="badge bg-warning">Suspendida</span>';
            
            $('#preview_membresia').html(membresia);
            $('#preview_vigencia').html(`${inicio} al ${fin}`);
            $('#preview_estado').html(estadoBadge);
            
            // Sugerir monto
            $('#monto').val(precio.toFixed(2));
            
            $('#historialCard').addClass('active');
        } else {
            $('#historialCard').removeClass('active');
        }
    });
    
    // Restaurar valores antiguos si existen
    @if(old('cliente_id'))
        $('#cliente_id').trigger('change');
        setTimeout(function() {
            $('#historial_membresia_id').val('{{old("historial_membresia_id")}}').trigger('change');
        }, 500);
    @endif
});
</script>
@endpush