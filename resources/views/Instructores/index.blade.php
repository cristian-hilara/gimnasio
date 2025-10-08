@extends('layouts.template')
@section('title', 'Instructores')

@push('css')
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
    .instructor-avatar {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<!--Aqui esta el mensaje de exito papus-->
@include('components.success-message')

<div class="container-fluid px-4">
    <h1 class="mt-4">Instructores</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Registro de Instructores</li>
    </ol>

    <div class="mb-4">
        <a href="{{route('instructores.create')}}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Añadir Nuevo Instructor
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de Instructores
        </div>

        <div class="table-responsive p-3">
            <table id="instructoresTable" class="table table-striped table-hover align-middle shadow-sm rounded">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Instructor</th>
                        <th>Especialidad</th>
                        <th>Experiencia</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($instructores as $instructor)
                    <tr id="instructor-{{ $instructor->id }}">
                        <td>{{ $instructor->id }}</td>
                        <td>
                            @if(optional($instructor->usuario)->foto)
                            <img src="{{ asset('storage/' . $instructor->usuario->foto) }}"
                                alt="Foto de {{ optional($instructor->usuario)->nombre ?? 'Instructor' }}"
                                class="rounded-circle instructor-avatar"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center instructor-avatar"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white" aria-label="Sin foto"></i>
                            </div>
                            @endif
                        </td>

                        <td>
                            <strong>
                                {{ optional($instructor->usuario)->nombre ?? 'Sin nombre' }}
                                {{ optional($instructor->usuario)->apellido ?? '' }}
                            </strong>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-envelope"></i>
                                {{ optional($instructor->usuario)->email ?? 'Sin correo' }}
                            </small>
                        </td>

                        <td>
                            @if($instructor->especialidad)
                            <span class="badge bg-primary">{{ $instructor->especialidad }}</span>
                            @else
                            <span class="text-muted">Sin especialidad</span>
                            @endif
                        </td>
                        <td>
                            @if($instructor->experiencia)
                            <span class="badge bg-info">{{ $instructor->experiencia }}</span>
                            @else
                            <span class="text-muted">No especificada</span>
                            @endif
                        </td>
                        <td>
                            @if($instructor->estado == 'activo')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> Activo
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle"></i> Inactivo
                            </span>
                            @endif
                        </td>
                        <td>{{ $instructor->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm" onclick="viewInstructor({{ $instructor->id }})"
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('instructores.edit', $instructor->id) }}"
                                    class="btn btn-warning btn-sm"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteInstructor({{ $instructor->id }})"
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> No hay instructores registrados
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Ver Instructor -->
<div class="modal fade" id="viewInstructorModal" tabindex="-1" aria-labelledby="viewInstructorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewInstructorModalLabel">
                    <i class="fas fa-user-circle me-2"></i> Detalles del Instructor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="viewInstructorContent">
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- Botones de exportación -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script>
    function viewInstructor(id) {
        $('#viewInstructorModal').modal('show');
        $('#viewInstructorContent').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `);

        $.ajax({
            url: `/instructores/${id}`,
            type: 'GET',
            success: function(data) {
                const usuario = data.usuario || {};
                const foto = usuario.foto ? `/storage/${usuario.foto}` : '/img/default-avatar.png';
                const nombre = usuario.nombre ?? 'Sin nombre';
                const apellido = usuario.apellido ?? '';
                const email = usuario.email ?? 'Sin correo';
                const telefono = usuario.telefono ?? 'N/A';
                const direccion = usuario.direccion ?? 'N/A';
                const especialidad = data.especialidad ?? 'N/A';
                const experiencia = data.experiencia ?? 'N/A';
                const estado = data.estado ?? 'inactivo';
                const fechaRegistro = new Date(data.created_at).toLocaleDateString('es-ES');
                const fechaActualizacion = new Date(data.updated_at).toLocaleDateString('es-ES');

                $('#viewInstructorContent').html(`
                    <div class="text-center mb-4">
                        <img src="${foto}" 
                             alt="Foto de ${nombre} ${apellido}"
                             class="rounded-circle"
                             style="width: 120px; height: 120px; object-fit: cover; border: 5px solid #17a2b8;">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-info"><i class="fas fa-user"></i> Información Personal</h6>
                            <hr>
                            <p><strong>Nombre:</strong> ${nombre} ${apellido}</p>
                            <p><strong>Email:</strong> ${email}</p>
                            <p><strong>Teléfono:</strong> ${telefono}</p>
                            <p><strong>Dirección:</strong> ${direccion}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info"><i class="fas fa-briefcase"></i> Información Profesional</h6>
                            <hr>
                            <p><strong>Especialidad:</strong> 
                                <span class="badge bg-primary">${especialidad}</span>
                            </p>
                            <p><strong>Experiencia:</strong> 
                                <span class="badge bg-info">${experiencia}</span>
                            </p>
                            <p><strong>Estado:</strong> 
                                <span class="badge ${estado === 'activo' ? 'bg-success' : 'bg-danger'}">
                                    ${estado.charAt(0).toUpperCase() + estado.slice(1)}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-info"><i class="fas fa-calendar"></i> Fechas</h6>
                            <hr>
                            <p><strong>Fecha de registro:</strong> ${fechaRegistro}</p>
                            <p><strong>Última actualización:</strong> ${fechaActualizacion}</p>
                        </div>
                    </div>
                `);
            },
            error: function() {
                $('#viewInstructorContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error al cargar la información del instructor
                    </div>
                `);
            }
        });
    }
</script>


@endpush