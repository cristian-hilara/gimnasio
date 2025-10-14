@extends('layouts.template')
@section('title', 'Pagos')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
@include('components.success-message')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-receipt"></i> Pagos</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Historial de Pagos</li>
            </ol>
        </div>
        <div>
            <a href="{{route('pagos.create')}}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Registrar Pago
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pagos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$pagos->count()}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Recaudado
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Bs. {{number_format($pagos->sum('monto'), 2)}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Mes Actual
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Bs. {{number_format($pagos->where('fecha_pago', '>=', now()->startOfMonth())->sum('monto'), 2)}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Promedio
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Bs. {{$pagos->count() > 0 ? number_format($pagos->avg('monto'), 2) : '0.00'}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <i class="fas fa-table me-1"></i> Tabla de Pagos
        </div>
        <div class="table-responsive p-3">
            <table id="pagosTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Membresía</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Referencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                    <tr>
                        <td>{{$pago->id}}</td>
                        <td>{{$pago->fecha_pago->format('d/m/Y')}}</td>
                        <td>
                            <strong>{{$pago->cliente->usuario->nombre}} {{$pago->cliente->usuario->apellido}}</strong>
                            <br>
                            <small class="text-muted">{{$pago->cliente->usuario->email}}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{$pago->historialMembresia->membresia->nombre}}
                            </span>
                        </td>
                        <td>
                            <strong class="text-success">Bs. {{number_format($pago->monto, 2)}}</strong>
                        </td>
                        <td>
                            <i class="fas {{$pago->metodo_pago_icono}}"></i> 
                            {{$pago->metodo_pago_texto}}
                        </td>
                        <td>
                            @if($pago->referencia_pago)
                            <code>{{$pago->referencia_pago}}</code>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm"onclick="viewPago({{$pago->id}})" 
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{route('pagos.edit', $pago->id)}}" 
                                    class="btn btn-warning btn-sm" 
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm"onclick="deletePago({{$pago->id}})" 
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div class="modal fade" id="viewPagoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-receipt"></i> Detalles del Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewPagoContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#pagosTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']]
    });
});

function viewPago(id) {
    $('#viewPagoModal').modal('show');
    $('#viewPagoContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `);

    $.ajax({
        url: `/pagos/${id}`,
        method: 'GET',
        success: function(data) {
            $('#viewPagoContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-user"></i> Información del Cliente</h6>
                        <hr>
                        <p><strong>Nombre:</strong> ${data.cliente.usuario.nombre} ${data.cliente.usuario.apellido}</p>
                        <p><strong>Email:</strong> ${data.cliente.usuario.email}</p>
                        <p><strong>Teléfono:</strong> ${data.cliente.usuario.telefono || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-receipt"></i> Información del Pago</h6>
                        <hr>
                        <p><strong>Fecha de pago:</strong> ${new Date(data.fecha_pago).toLocaleDateString('es-ES')}</p>
                        <p><strong>Monto:</strong> <span class="text-success fs-4">Bs. ${parseFloat(data.monto).toFixed(2)}</span></p>
                        <p><strong>Método:</strong> <i class="fas ${data.metodo_pago_icono}"></i> ${data.metodo_pago_texto}</p>
                        <p><strong>Referencia:</strong> ${data.referencia_pago ? `<code>${data.referencia_pago}</code>` : 'N/A'}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-info"><i class="fas fa-id-card"></i> Membresía Asociada</h6>
                        <hr>
                        <p><strong>Membresía:</strong> ${data.historial_membresia.membresia.nombre}</p>
                        <p><strong>Vigencia:</strong> ${new Date(data.historial_membresia.fecha_inicio).toLocaleDateString('es-ES')} - ${new Date(data.historial_membresia.fecha_fin).toLocaleDateString('es-ES')}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge ${data.historial_membresia.estado_badge.clase}">
                                ${data.historial_membresia.estado_badge.texto}
                            </span>
                        </p>
                    </div>
                </div>
            `);
        },
        error: function() {
            $('#viewPagoContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos
                </div>
            `);
        }
    });
}

function deletePago(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/pagos/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('¡Eliminado!', response.message, 'success')
                            .then(() => location.reload());
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Error al eliminar', 'error');
                }
            });
        }
    });
}
</script>
@endpush