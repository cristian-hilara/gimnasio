@extends('layouts.template')
@section('title','Recepcionistas')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .btn-action {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')

@include('components.success-message')

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-cash-register"></i> Recepcionistas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Registro de Recepcionistas</li>
    </ol>

    <div class="mb-4">
        <a href="{{route('recepcionistas.create')}}" class="btn btn-primary btn-lg shadow">
            <i class="fas fa-user-plus"></i> Añadir Nuevo Recepcionista
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header card-header-custom">
            <i class="fas fa-table me-1"></i>
            Tabla de Recepcionistas
        </div>

        <div class="table-responsive p-3">
            <table id="recepcionistasTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Recepcionista</th>
                        <th>Turno</th>
                        <th>Estado</th>
                        <th>Fecha registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ( $recepcionistas as $recep )
                    <tr>
                        <td>{{$recep->id}}</td>
                        <td>
                            <strong>{{$recep->usuario->nombre}} {{$recep->usuario->apellido}}</strong>
                        </td>
                        <td>
                            @switch($recep->turno)
                            @case('mañana')
                            <span class="badge bg-success"><i class="fas fa-sun"></i> Mañana</span>
                            @break
                            @case('tarde')
                            <span class="badge bg-primary"><i class="fas fa-cloud-sun"></i> Tarde</span>
                            @break
                            @case('noche')
                            <span class="badge bg-secondary"><i class="fas fa-moon"></i> Noche</span>
                            @break
                            @default
                            <span class="badge bg-warning">Sin turno</span>
                            @endswitch
                        </td>
                        <td>
                            @if($recep->estado == 'activo')
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($recep->created_at)->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm btn-action"
                                    onclick="showRecepcionistaModal('{{$recep->id}}')" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('recepcionistas.edit', $recep->id) }}"
                                    class="btn btn-warning btn-sm btn-action" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-action"
                                    onclick="deleteRecepcionista('{{$recep->id}}')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="alert alert-info text-center my-3">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <h5>No hay recepcionistas registrados</h5>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="recepcionistaModal" tabindex="-1" aria-labelledby="recepcionistaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header card-header-custom">
                <h5 class="modal-title" id="recepcionistaModalLabel">
                    <i class="fas fa-user-circle"></i> Detalles del Recepcionista
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="recepcionistaModalBody">
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function() {
        // Nota: He cambiado 'usuariosTable' por 'recepcionistasTable'
        $('#recepcionistasTable').DataTable({
            language: {
                url:'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            order: [
                [1, 'asc']
            ]
        });
    });

    function showRecepcionistaModal(id) {
        $('#recepcionistaModal').modal('show');
        $('#recepcionistaModalBody').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `);

        // **IMPORTANTE**: Asegúrate de que esta ruta '/recepcionistas/' esté definida en tu web.php
        $.ajax({
            url: `/recepcionistas/${id}`,
            method: 'GET',
            success: function(data) {
                const fotoUrl = data.usuario.foto ?
                    `/storage/${data.usuario.foto}` :
                    '/img/default-avatar.png';

                let estadoBadge = data.estado === 'activo' ?
                    '<span class="badge bg-success">Activo</span>' :
                    '<span class="badge bg-danger">Inactivo</span>';

                let turnoIcon = '';
                let turnoClass = '';
                switch (data.turno) {
                    case 'mañana':
                        turnoIcon = 'fas fa-sun';
                        turnoClass = 'bg-success';
                        break;
                    case 'tarde':
                        turnoIcon = 'fas fa-cloud-sun';
                        turnoClass = 'bg-primary';
                        break;
                    case 'noche':
                        turnoIcon = 'fas fa-moon';
                        turnoClass = 'bg-secondary';
                        break;
                    default:
                        turnoIcon = 'fas fa-question-circle';
                        turnoClass = 'bg-warning';
                }
                let turnoBadge = `<span class="badge ${turnoClass}"><i class="${turnoIcon}"></i> ${data.turno.charAt(0).toUpperCase() + data.turno.slice(1)}</span>`;

                $('#recepcionistaModalBody').html(`
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="${fotoUrl}" 
                            alt="Foto de ${data.usuario.nombre}"
                            class="rounded-circle mb-3"
                            style="width: 120px; height: 120px; object-fit: cover; border: 5px solid #667eea;">
                        <h5>${data.usuario.nombre} ${data.usuario.apellido}</h5>
                        <p>${estadoBadge}</p>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-primary"><i class="fas fa-id-card"></i> Información del Usuario</h6>
                        <hr>
                        <p><strong>Email:</strong> ${data.usuario.email || 'N/A'}</p>
                        <p><strong>Teléfono:</strong> ${data.usuario.telefono || 'N/A'}</p>
                        <p><strong>Dirección:</strong> ${data.usuario.direccion || 'N/A'}</p>
                        
                        <h6 class="text-primary mt-4"><i class="fas fa-briefcase"></i> Detalles de Recepcionista</h6>
                        <hr>
                        <p><strong>ID Recepcionista:</strong> ${data.id}</p>
                        <p><strong>Turno:</strong> ${turnoBadge}</p>
                        <p><strong>Fecha de Contratación:</strong> ${data.fecha_contratacion || 'N/A'}</p>
                        <p><strong>Registrado desde:</strong> ${new Date(data.created_at).toLocaleDateString('es-ES')}</p>
                    </div>
                </div>
            `);
            },
            error: function() {
                $('#recepcionistaModalBody').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos del recepcionista.
                </div>
            `);
            }
        });
    }

    function deleteRecepcionista(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede revertir. ¡Eliminará al recepcionista y su usuario!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // **IMPORTANTE**: Asegúrate de que esta ruta '/recepcionistas/' esté definida para el método DELETE
                $.ajax({
                    url: `/recepcionistas/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            '¡Eliminado!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error',
                            xhr.responseJSON?.message || 'Error al eliminar el recepcionista',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endpush