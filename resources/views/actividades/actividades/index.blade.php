@extends('layouts.template')
@section('title', 'Actividades')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<style>
    .activity-card {
        transition: all 0.3s ease;
        border-left: 4px solid #667eea;
    }

    .activity-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .type-badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
@include('components.success-message')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-dumbbell"></i> Actividades</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('actividad_horarios.index')}}">Horarios</a></li>
                <li class="breadcrumb-item active">Actividades</li>
            </ol>
        </div>
        <div>
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#tipoActividadModal">
                <i class="fas fa-tags"></i> Gestionar Tipos
            </button>
            <a href="{{route('actividades.create')}}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nueva Actividad
            </a>
        </div>
    </div>

    <!-- Tarjetas de Tipos de Actividad -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-tags"></i> Tipos de Actividad Registrados
                </div>
                <div class="card-body">
                    <div class="row" id="tiposContainer">
                        @forelse($tiposActividad as $tipo)
                        <div class="col-md-3 mb-3">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        <span class="badge type-badge bg-success">{{$tipo->nombre}}</span>
                                    </h5>
                                    <p class="card-text small text-muted">
                                        {{$tipo->descripcion ?? 'Sin descripción'}}
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-list"></i>
                                        {{$tipo->actividades->count()}} actividades
                                    </small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay tipos de actividad. Haz clic en "Gestionar Tipos" para agregar.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Actividades -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i> Tabla de Actividades
        </div>
        <div class="table-responsive p-3">
            <table id="actividadesTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Horarios</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actividades as $actividad)
                    <tr>
                        <td>{{$actividad->id}}</td>
                        <td><strong>{{$actividad->nombre}}</strong></td>
                        <td>
                            <span class="badge bg-info">{{$actividad->tipoActividad->nombre}}</span>
                        </td>
                        <td>
                            @if($actividad->descripcion)
                            <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                title="{{$actividad->descripcion}}">
                                {{$actividad->descripcion}}
                            </span>
                            @else
                            <span class="text-muted">Sin descripción</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-calendar"></i> {{$actividad->horarios->count()}} horarios
                            </span>
                        </td>
                        <td>{{$actividad->created_at->format('d/m/Y')}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm"onclick="viewActividad({{$actividad->id}})"
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{route('actividades.edit', $actividad->id)}}"
                                    class="btn btn-warning btn-sm"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm"onclick="deleteActividad({{$actividad->id}})"
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No hay actividades registradas
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Ver Actividad -->
<div class="modal fade" id="viewActividadModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalles de la Actividad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewActividadContent">
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

<!-- Modal para Gestionar Tipos de Actividad -->
<div class="modal fade" id="tipoActividadModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-tags"></i> Gestionar Tipos de Actividad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para agregar nuevo tipo -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <i class="fas fa-plus"></i> Agregar Nuevo Tipo
                    </div>
                    <div class="card-body">
                        <form id="formNuevoTipo">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="form-label">Nombre *</label>
                                    <input type="text" name="nombre" class="form-control"
                                        placeholder="Ej: Spinning, Yoga, Pilates" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Descripción</label>
                                    <input type="text" name="descripcion" class="form-control"
                                        placeholder="Descripción breve">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-save"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de tipos existentes -->
                <div class="card">
                    <div class="card-header bg-light">
                        <i class="fas fa-list"></i> Tipos Registrados
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tiposTable">
                                <thead class="table-success">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Actividades</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tiposActividad as $tipo)
                                    <tr id="tipo-{{$tipo->id}}">
                                        <td>{{$tipo->id}}</td>
                                        <td>
                                            <span class="badge bg-success">{{$tipo->nombre}}</span>
                                        </td>
                                        <td>{{$tipo->descripcion ?? 'N/A'}}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{$tipo->actividades->count()}} actividades
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"onclick="editTipo({{$tipo->id}}, '{{$tipo->nombre}}', '{{$tipo->descripcion}}')"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm"onclick="deleteTipo({{$tipo->id}})"
                                                title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Tipo -->
<div class="modal fade" id="editTipoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i> Editar Tipo de Actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditTipo">
                    <input type="hidden" id="edit_tipo_id">
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" id="edit_descripcion" class="form-control">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
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
        $('#actividadesTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [
                [0, 'desc']
            ]
        });
    });

    // Ver detalles de actividad
    function viewActividad(id) {
        $('#viewActividadModal').modal('show');
        $('#viewActividadContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `);

        $.ajax({
            url: `/actividades/${id}`,
            method: 'GET',
            success: function(data) {
                let horariosHtml = '';
                if (data.horarios && data.horarios.length > 0) {
                    horariosHtml = '<ul class="list-group">';
                    data.horarios.forEach(h => {
                        horariosHtml += `
                        <li class="list-group-item">
                            <strong>${h.dia_semana}</strong>: ${h.hora_inicio} - ${h.hora_fin}
                            <span class="badge bg-warning text-dark float-end">${h.cupo_maximo} cupos</span>
                        </li>
                    `;
                    });
                    horariosHtml += '</ul>';
                } else {
                    horariosHtml = '<p class="text-muted">No hay horarios asignados</p>';
                }

                $('#viewActividadContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-dumbbell"></i> Información General</h6>
                        <hr>
                        <p><strong>Nombre:</strong> ${data.nombre}</p>
                        <p><strong>Tipo:</strong> <span class="badge bg-info">${data.tipo_actividad.nombre}</span></p>
                        <p><strong>Descripción:</strong> ${data.descripcion || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-calendar"></i> Horarios Asignados</h6>
                        <hr>
                        ${horariosHtml}
                    </div>
                </div>
            `);
            },
            error: function() {
                $('#viewActividadContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos
                </div>
            `);
            }
        });
    }

    // Eliminar actividad
    function deleteActividad(id) {
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
                    url: `/actividades/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('¡Eliminados!', response.message, 'success')
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

    // Crear nuevo tipo
    $('#formNuevoTipo').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("tipos_actividad.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nombre: $('input[name="nombre"]').val(),
                descripcion: $('input[name="descripcion"]').val()
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success')
                        .then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Error al crear', 'error');
            }
        });
    });

    // Editar tipo
    function editTipo(id, nombre, descripcion) {
        $('#edit_tipo_id').val(id);
        $('#edit_nombre').val(nombre);
        $('#edit_descripcion').val(descripcion);
        $('#editTipoModal').modal('show');
    }

    $('#formEditTipo').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_tipo_id').val();

        $.ajax({
            url: `/tipos_actividad/${id}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                nombre: $('#edit_nombre').val(),
                descripcion: $('#edit_descripcion').val()
            },
            success: function(response) {
                if (response.success) {
                    $('#editTipoModal').modal('hide');
                    Swal.fire('¡Actualizado!', response.message, 'success')
                        .then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Error al actualizar', 'error');
            }
        });
    });

    // Eliminar tipo
    function deleteTipo(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'No podrás eliminar este tipo si tiene actividades asociadas',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/tipos_actividad/${id}`,
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