@extends('layouts.template')
@section('title', 'Historial de Membresías')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<style>
    .progress-custom {
        height: 25px;
        border-radius: 10px;
    }

    .estado-vigente {
        border-left: 4px solid #28a745;
    }

    .estado-vencida {
        border-left: 4px solid #dc3545;
    }

    .estado-suspendida {
        border-left: 4px solid #ffc107;
    }
</style>
@endpush

@section('content')
@include('components.success-message')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-history"></i> Historial de Membresías</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Gestión de Membresías Activas</li>
            </ol>
        </div>
        <div>
            <a href="{{route('inscripciones.create')}}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nueva Inscripción
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
                                Vigentes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$historiales->where('estado_membresia', 'vigente')->count()}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Vencidas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$historiales->where('estado_membresia', 'vencida')->count()}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
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
                                Suspendidas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$historiales->where('estado_membresia', 'suspendida')->count()}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause-circle fa-2x text-warning"></i>
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
                                Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$historiales->count()}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i> Tabla de Historial
        </div>
        <div class="table-responsive p-3">
            <table id="historialesTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Membresía</th>
                        <th>Vigencia</th>
                        <th>Progreso</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historiales as $historial)
                    <tr class="estado-{{$historial->estado_membresia}}">
                        <td>{{$historial->id}}</td>
                        <td>
                            <strong>{{$historial->cliente->usuario->nombre}} {{$historial->cliente->usuario->apellido}}</strong>
                            <br>
                            <small class="text-muted">{{$historial->cliente->usuario->email}}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{$historial->membresia->nombre}}</span>
                            <br>
                            <small class="text-muted">{{$historial->dias_totales}} días</small>
                        </td>
                        <td>
                            <small>
                                <i class="fas fa-calendar-alt"></i>
                                {{$historial->fecha_inicio->format('d/m/Y')}}
                            </small>
                            <br>
                            <small>
                                <i class="fas fa-calendar-check"></i>
                                {{$historial->fecha_fin->format('d/m/Y')}}
                            </small>
                            <br>
                            @if($historial->estado_membresia == 'vigente')
                            <small class="text-muted">
                                <i class="fas fa-hourglass-half"></i>
                                {{$historial->dias_restantes}} días restantes
                            </small>
                            @endif
                        </td>
                        <td>
                            @if($historial->estado_membresia == 'vigente')
                            <div class="progress progress-custom">
                                <div class="progress-bar 
                                    @if($historial->porcentaje_progreso < 50) bg-success
                                    @elseif($historial->porcentaje_progreso < 80) bg-warning
                                    @else bg-danger
                                    @endif"
                                    role="progressbar"style="width: {{$historial->porcentaje_progreso}}%"
                                    aria-valuenow="{{$historial->porcentaje_progreso}}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{$historial->porcentaje_progreso}}%
                                </div>
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($historial->descuento_aplicado > 0)
                            <del class="text-muted">Bs. {{number_format($historial->precio_original, 2)}}</del>
                            <br>
                            <strong class="text-success">Bs. {{number_format($historial->precio_final, 2)}}</strong>
                            <br>
                            <small class="badge bg-success">{{$historial->porcentaje_descuento}}% OFF</small>
                            @else
                            <strong>Bs. {{number_format($historial->precio_final, 2)}}</strong>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{$historial->estado_badge['clase']}}">
                                {{$historial->estado_badge['texto']}}
                            </span>
                            @if($historial->promocion)
                            <br>
                            <small class="badge bg-secondary">
                                <i class="fas fa-tag"></i> {{$historial->promocion->nombre}}
                            </small>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm"onclick="viewHistorial({{$historial->id}})"
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{route('historial-membresias.edit', $historial->id)}}"
                                    class="btn btn-warning btn-sm"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($historial->estado_membresia == 'vigente')
                                <button class="btn btn-secondary btn-sm"onclick="suspendHistorial({{$historial->id}})"
                                    title="Suspender">
                                    <i class="fas fa-pause"></i>
                                </button>
                                @elseif($historial->estado_membresia == 'suspendida')
                                <button class="btn btn-success btn-sm"onclick="reactivateHistorial({{$historial->id}})"
                                    title="Reactivar">
                                    <i class="fas fa-play"></i>
                                </button>
                                @endif
                                <button class="btn btn-danger btn-sm"onclick="deleteHistorial({{$historial->id}})"
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
<div class="modal fade" id="viewHistorialModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalles del Historial
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewHistorialContent">
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
        $('#historialesTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [
                [0, 'desc']
            ]
        });
    });

    function viewHistorial(id) {
        $('#viewHistorialModal').modal('show');
        $('#viewHistorialContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `);

        $.ajax({
            url: `/historial-membresias/${id}`,
            method: 'GET',
            success: function(data) {
                let pagosHtml = '';
                if (data.pagos && data.pagos.length > 0) {
                    pagosHtml = '<ul class="list-group">';
                    data.pagos.forEach(pago => {
                        pagosHtml += `
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>${pago.metodo_pago_texto}</strong>
                                    <br>
                                    <small class="text-muted">Fecha: ${new Date(pago.fecha_pago).toLocaleDateString('es-ES')}</small>
                                    ${pago.referencia_pago ? `<br><small>Ref: ${pago.referencia_pago}</small>` : ''}
                                </div>
                                <div>
                                    <span class="badge bg-success fs-6">Bs. ${parseFloat(pago.monto).toFixed(2)}</span>
                                </div>
                            </div>
                        </li>
                    `;
                    });
                    pagosHtml += '</ul>';
                } else {
                    pagosHtml = '<p class="text-muted">No hay pagos registrados</p>';
                }

                const promoHtml = data.promocion ? `
                <div class="alert alert-success">
                    <i class="fas fa-tag"></i> <strong>Promoción aplicada:</strong> ${data.promocion.nombre}
                    <br>
                    <small>Descuento: Bs. ${parseFloat(data.descuento_aplicado).toFixed(2)} (${data.porcentaje_descuento}%)</small>
                </div>
            ` : '';

                $('#viewHistorialContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-user"></i> Información del Cliente</h6>
                        <hr>
                        <p><strong>Nombre:</strong> ${data.cliente.usuario.nombre} ${data.cliente.usuario.apellido}</p>
                        <p><strong>Email:</strong> ${data.cliente.usuario.email}</p>
                        <p><strong
                        >Teléfono:</strong> ${data.cliente.usuario.telefono || 'N/A'}</p>
                        <p><strong>Peso:</strong> ${data.cliente.peso || 'N/A'} kg</p>
                        <p><strong>Altura:</strong> ${data.cliente.altura || 'N/A'} m</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-id-card"></i> Información de la Membresía</h6>
                        <hr>
                        <p><strong>Membresía:</strong> ${data.membresia.nombre}</p>
                        <p><strong>Duración:</strong> ${data.dias_totales} días</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge ${data.estado_badge.clase}">${data.estado_badge.texto}</span>
                        </p>
                        ${data.estado_membresia === 'vigente' ? `
                            <p><strong>Días restantes:</strong> ${data.dias_restantes} días</p>
                            <p><strong>Progreso:</strong></p>
                            <div class="progress progress-custom mb-2">
                                <div class="progress-bar bg-success" style="width: ${data.porcentaje_progreso}%">
                                    ${data.porcentaje_progreso}%
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-calendar"></i> Vigencia</h6>
                        <hr>
                        <p><strong>Fecha inicio:</strong> ${new Date(data.fecha_inicio).toLocaleDateString('es-ES')}</p>
                        <p><strong>Fecha fin:</strong> ${new Date(data.fecha_fin).toLocaleDateString('es-ES')}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-dollar-sign"></i> Información de Pago</h6>
                        <hr>
                        <p><strong>Precio original:</strong> Bs. ${parseFloat(data.precio_original).toFixed(2)}</p>
                        ${data.descuento_aplicado > 0 ? `
                            <p><strong>Descuento aplicado:</strong> <span class="text-success">Bs. ${parseFloat(data.descuento_aplicado).toFixed(2)}</span></p>
                        ` : ''}
                        <p><strong>Precio final:</strong> <span class="text-primary fs-5">Bs. ${parseFloat(data.precio_final).toFixed(2)}</span></p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        ${promoHtml}
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-info"><i class="fas fa-receipt"></i> Historial de Pagos</h6>
                        <hr>
                        ${pagosHtml}
                    </div>
                </div>
            `);
            },
            error: function() {
                $('#viewHistorialContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos
                </div>
            `);
            }
        });
    }

    function suspendHistorial(id) {
        Swal.fire({
            title: '¿Suspender membresía?',
            text: 'El cliente no podrá acceder al gimnasio',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, suspender',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/historial-membresias/${id}/suspend`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('¡Suspendida!', response.message, 'success')
                                .then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Error al suspender', 'error');
                    }
                });
            }
        });
    }

    function reactivateHistorial(id) {
        Swal.fire({
            title: '¿Reactivar membresía?',
            text: 'El cliente podrá acceder nuevamente al gimnasio',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, reactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/historial-membresias/${id}/reactivate`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('¡Reactivada!', response.message, 'success')
                                .then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Error al reactivar', 'error');
                    }
                });
            }
        });
    }

    function deleteHistorial(id) {
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
                    url: `/historial-membresias/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
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