@extends('layouts.template')
@section('title', 'Horarios de Actividades')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
    .schedule-card {
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }
    .schedule-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .day-badge {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
    .time-display {
        font-weight: 600;
        color: #667eea;
    }
</style>
@endpush

@section('content')
@include('components.success-message')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-calendar-alt"></i> Horarios de Actividades</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Gestión de Horarios</li>
            </ol>
        </div>
        <div>
            <a href="{{route('actividades.index')}}" class="btn btn-info me-2">
                <i class="fas fa-dumbbell"></i> Gestionar Actividades
            </a>
            <a href="{{route('actividad_horarios.create')}}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nuevo Horario
            </a>
        </div>
    </div>

    <!-- Filtros rápidos -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-filter"></i> Filtrar por día</label>
                    <select id="filterDay" class="form-select">
                        <option value="">Todos los días</option>
                        <option value="lunes">Lunes</option>
                        <option value="martes">Martes</option>
                        <option value="miércoles">Miércoles</option>
                        <option value="jueves">Jueves</option>
                        <option value="viernes">Viernes</option>
                        <option value="sábado">Sábado</option>
                        <option value="domingo">Domingo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-toggle-on"></i> Filtrar por estado</label>
                    <select id="filterEstado" class="form-select">
                        <option value="">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Horarios -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i> Tabla de Horarios
        </div>
        <div class="table-responsive p-3">
            <table id="horariosTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Actividad</th>
                        <th>Tipo</th>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Instructor</th>
                        <th>Sala</th>
                        <th>Cupos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($horarios as $horario)
                    <tr data-dia="{{$horario->dia_semana}}" data-estado="{{$horario->estado ? '1' : '0'}}">
                        <td>{{$horario->id}}</td>
                        <td>
                            <strong>{{$horario->actividad->nombre}}</strong>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{$horario->actividad->tipoActividad->nombre}}
                            </span>
                        </td>
                        <td>
                            <span class="badge day-badge bg-primary">
                                {{ucfirst($horario->dia_semana)}}
                            </span>
                        </td>
                        <td class="time-display">
                            <i class="fas fa-clock"></i> 
                            {{date('H:i', strtotime($horario->hora_inicio))}} - 
                            {{date('H:i', strtotime($horario->hora_fin))}}
                        </td>
                        <td>
                            <small>
                                <i class="fas fa-user-tie"></i>
                                {{$horario->instructor->usuario->nombre}} 
                                {{$horario->instructor->usuario->apellido}}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                <i class="fas fa-door-open"></i> {{$horario->sala->nombre}}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-users"></i> {{$horario->cupo_maximo}}
                            </span>
                        </td>
                        <td>
                            @if($horario->estado)
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm"onclick="viewHorario({{$horario->id}})" 
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{route('actividad_horarios.edit', $horario->id)}}" 
                                    class="btn btn-warning btn-sm" 
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm"onclick="deleteHorario({{$horario->id}})" 
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No hay horarios registrados
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div class="modal fade" id="viewHorarioModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalles del Horario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewHorarioContent">
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
let table;

$(document).ready(function() {
    table = $('#horariosTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        responsive: true,
        pageLength: 10,
        order: [[3, 'asc'], [4, 'asc']],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });

    // Filtro por día
    $('#filterDay').on('change', function() {
        const dia = $(this).val();
        if (dia) {
            table.column(3).search(dia).draw();
        } else {
            table.column(3).search('').draw();
        }
    });

    // Filtro por estado
    $('#filterEstado').on('change', function() {
        const estado = $(this).val();
        if (estado !== '') {
            table.column(8).search(estado === '1' ? 'Activo' : 'Inactivo').draw();
        } else {
            table.column(8).search('').draw();
        }
    });
});

function viewHorario(id) {
    $('#viewHorarioModal').modal('show');
    $('#viewHorarioContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `);

    $.ajax({
        url: `/actividad_horarios/${id}`,
        method: 'GET',
        success: function(data) {
            $('#viewHorarioContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-dumbbell"></i> Información de la Actividad</h6>
                        <hr>
                        <p><strong>Actividad:</strong> ${data.actividad.nombre}</p>
                        <p><strong>Tipo:</strong> <span class="badge bg-info">${data.actividad.tipo_actividad.nombre}</span></p>
                        <p><strong>Descripción:</strong> ${data.actividad.descripcion || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-clock"></i> Información del Horario</h6>
                        <hr>
                        <p><strong>Día:</strong> <span class="badge bg-primary">${data.dia_nombre}</span></p>
                        <p><strong>Hora inicio:</strong> ${data.hora_inicio}</p>
                        <p><strong>Hora fin:</strong> ${data.hora_fin}</p>
                        <p><strong>Cupo máximo:</strong> <span class="badge bg-warning text-dark">${data.cupo_maximo} personas</span></p>
                        <p><strong>Estado:</strong> 
                            <span class="badge ${data.estado ? 'bg-success' : 'bg-danger'}">
                                ${data.estado ? 'Activo' : 'Inactivo'}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-user-tie"></i> Instructor</h6>
                        <hr>
                        <p><strong>Nombre:</strong> ${data.instructor.usuario.nombre} ${data.instructor.usuario.apellido}</p>
                        <p><strong>Email:</strong> ${data.instructor.usuario.email}</p>
                        <p><strong>Especialidad:</strong> ${data.instructor.especialidad || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-door-open"></i> Sala</h6>
                        <hr>
                        <p><strong>Nombre:</strong> ${data.sala.nombre}</p>
                        <p><strong>Capacidad:</strong> ${data.sala.capacidad} personas</p>
                        <p><strong>Ubicación:</strong> ${data.sala.ubicacion || 'N/A'}</p>
                    </div>
                </div>
            `);
        },
        error: function() {
            $('#viewHorarioContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos
                </div>
            `);
        }
    });
}

function deleteHorario(id) {
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
                url: `/actividad_horarios/${id}`,
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