@extends('layouts.template')
@section('title', 'Membresías')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<style>
    .membresia-card {
        transition: all 0.3s ease;
        border-left: 4px solid #28a745;
        height: 100%;
    }
    .membresia-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .price-tag {
        font-size: 2rem;
        font-weight: bold;
        color: #28a745;
    }
    .duration-badge {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
</style>
@endpush

@section('content')
@include('components.success-message')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-id-card"></i> Membresías</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Gestión de Membresías</li>
            </ol>
        </div>
        <div>
            <a href="{{route('membresias.create')}}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nueva Membresía
            </a>
        </div>
    </div>

    <!-- Vista de Tarjetas -->
    <div class="row mb-4">
        @forelse($membresias as $membresia)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card membresia-card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-id-card fa-3x text-success"></i>
                    </div>
                    
                    <h4 class="card-title mb-3">{{$membresia->nombre}}</h4>
                    
                    <div class="price-tag mb-3">
                        Bs. {{number_format($membresia->precio, 2)}}
                    </div>
                    
                    <span class="badge duration-badge bg-info mb-3">
                        <i class="fas fa-calendar-alt"></i> {{$membresia->duracion_texto}}
                    </span>
                    
                    @if($membresia->descripcion)
                    <p class="text-muted small mb-3">
                        {{Str::limit($membresia->descripcion, 80)}}
                    </p>
                    @endif
                    
                    <div class="mb-3">
                        @if($membresia->estado)
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> Activa
                        </span>
                        @else
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle"></i> Inactiva
                        </span>
                        @endif
                    </div>
                    

                </div>
                <div class="card-footer text-muted small">
                    <i class="fas fa-calendar"></i> 
                    Creada: {{$membresia->created_at->format('d/m/Y')}}
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h5>No hay membresías registradas</h5>
                <p>Comienza agregando tu primera membresía</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Tabla de Membresías -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <i class="fas fa-table me-1"></i> Tabla de Membresías
        </div>
        <div class="table-responsive p-3">
            <table id="membresiasTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Duración</th>
                        <th>Precio</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($membresias as $membresia)
                    <tr>
                        <td>{{$membresia->id}}</td>
                        <td><strong>{{$membresia->nombre}}</strong></td>
                        <td>
                            <span class="badge bg-info">
                                <i class="fas fa-calendar-alt"></i> {{$membresia->duracion_texto}}
                            </span>
                        </td>
                        <td class="text-success fw-bold">Bs. {{number_format($membresia->precio, 2)}}</td>
                        <td>
                            @if($membresia->descripcion)
                            <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                title="{{$membresia->descripcion}}">
                                {{$membresia->descripcion}}
                            </span>
                            @else
                            <span class="text-muted">Sin descripción</span>
                            @endif
                        </td>
                        <td>
                            @if($membresia->estado)
                            <span class="badge bg-success">Activa</span>
                            @else
                            <span class="badge bg-danger">Inactiva</span>
                            @endif
                        </td>
                        <td>{{$membresia->created_at->format('d/m/Y')}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm"onclick="viewMembresia({{$membresia->id}})" 
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{route('membresias.edit', $membresia->id)}}" 
                                    class="btn btn-warning btn-sm" 
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm"onclick="deleteMembresia({{$membresia->id}})" 
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
<div class="modal fade" id="viewMembresiaModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-id-card"></i> Detalles de la Membresía
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewMembresiaContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
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
    $('#membresiasTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']]
    });
});

function viewMembresia(id) {
    $('#viewMembresiaModal').modal('show');
    $('#viewMembresiaContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `);

    $.ajax({
        url: `/membresias/${id}`,
        method: 'GET',
        success: function(data) {
            const formatoFecha = (iso) => {
                    const fecha = new Date(iso);
                    return new Intl.DateTimeFormat('es-BO', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    }).format(fecha);
                };
            let promocionesHtml = '';
            if (data.promociones && data.promociones.length > 0) {
                promocionesHtml = '<ul class="list-group">';
                data.promociones.forEach(promo => {
                    promocionesHtml += `
                        <li class="list-group-item">
                            <strong>${promo.nombre}</strong>
                            <span class="badge bg-success float-end">Bs. ${promo.pivot.precio_promocional}</span>
                            <br>
                            <small class="fas fa-tags class="text-info mt-3">${formatoFecha(promo.fecha_inicio)}   al   ${formatoFecha(promo.fecha_fin)}</small>
                        </li>
                    `;
                });
                promocionesHtml += '</ul>';
            } else {
                promocionesHtml = '<p class="text-muted">No tiene promociones activas</p>';
            }

            $('#viewMembresiaContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-info-circle"></i> Información General</h6>
                        <hr>
                        <p><strong>Nombre:</strong> ${data.nombre}</p>
                        <p><strong>Duración:</strong> 
                            <span class="badge bg-info">${data.duracion_texto}</span>
                        </p>
                        <p><strong>Precio:</strong> 
                            <span class="text-success fw-bold fs-4">Bs. ${parseFloat(data.precio).toFixed(2)}</span>
                        </p>
                        <p><strong>Estado:</strong> 
                            <span class="badge ${data.estado ? 'bg-success' : 'bg-danger'}">
                                ${data.estado ? 'Activa' : 'Inactiva'}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-align-left"></i> Descripción</h6>
                        <hr>
                        <p>${data.descripcion || 'Sin descripción'}</p>
                        
                        <h6 class="text-info mt-3"><i class="fas fa-tags"></i> Promociones Aplicables</h6>
                        <hr>
                        ${promocionesHtml}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-info"><i class="fas fa-calendar"></i> Fechas</h6>
                        <hr>
                        <p><strong>Fecha de registro:</strong> ${new Date(data.created_at).toLocaleDateString('es-ES')}</p>
                        <p><strong>Última actualización:</strong> ${new Date(data.updated_at).toLocaleDateString('es-ES')}</p>
                    </div>
                </div>
            `);
        },
        error: function() {
            $('#viewMembresiaContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos
                </div>
            `);
        }
    });
}

function deleteMembresia(id) {
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
                url: `/membresias/${id}`,
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