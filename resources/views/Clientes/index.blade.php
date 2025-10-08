@extends('layouts.template')
@section('title','Clientes')

@push('css')
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS -->
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
    <h1 class="mt-4"><i class="fas fa-user-friends"></i> Clientes</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Registro de Clientes</li>
    </ol>

    <div class="mb-4">
        <a href="{{route('clientes.create')}}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Añadir Nuevo Cliente
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header card-header-custom">
            <i class="fas fa-table me-1"></i>
            Tabla de Recepcionistas
        </div>

        <div class="table-responsive p-3">
            <table id="clientesTable" class="table table-striped table-hover align-middle shadow-sm rounded">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Cliente</th>
                        <th>Peso (kg)</th>
                        <th>Altura (m)</th>
                        <th>IMC</th>
                        <th>Estado</th>
                        <th>Fecha registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $clientes as $client )
                    <tr>
                        <td>{{$client->id}}</td>
                        <td class="d-flex align-items-center gap-2">
                            <img src="{{ $client->usuario->foto ? asset('storage/' . $client->usuario->foto) : asset('images/default-user.png') }}"
                                alt="Foto de {{ $client->usuario->nombre }}"
                                class="rounded-circle"
                                width="40" height="40">
                            <strong>{{ $client->usuario->nombre }} {{ $client->usuario->apellido }}</strong>
                        </td>
                        <td>{{$client->peso ?? 'N/A'}}</td>
                        <td>{{$client->altura ?? 'N/A'}}</td>
                        <td>
                            @if($client->imc)
                            <span class="badge 
                                    @if($client->imc < 18.5) bg-warning
                                    @elseif($client->imc < 25) bg-success
                                    @elseif($client->imc < 30) bg-info
                                    @else bg-danger
                                    @endif
                                ">
                                {{$client->imc}}
                            </span>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($client->estado == 'activo')
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($client->created_at)->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm"onclick="showClienteModal({{$client->id}})"
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{route('clientes.edit', $client->id)}}"
                                    class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm"onclick="deleteCliente({{$client->id}})"
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

<!-- Modal para mostrar detalles del cliente -->
<div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="clienteModalLabel">
                    <i class="fas fa-user-circle"></i> Detalles del Cliente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="clienteModalBody">
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
    $(document).ready(function() {
        $('#clientesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });

    function showClienteModal(id) {
        $('#clienteModal').modal('show');
        $('#clienteModalBody').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `);

        $.ajax({
            url: `/clientes/${id}`,
            method: 'GET',
            success: function(data) {
                let imc = '';
                if (data.imc) {
                    let badgeClass = '';
                    if (data.imc < 18.5) badgeClass = 'bg-warning';
                    else if (data.imc < 25) badgeClass = 'bg-success';
                    else if (data.imc < 30) badgeClass = 'bg-info';
                    else badgeClass = 'bg-danger';

                    imc = `
                    <p><strong>IMC:</strong> <span class="badge ${badgeClass}">${data.imc}</span></p>
                    <p><strong>Clasificación:</strong> ${data.clasificacion_imc}</p>
                `;
                }

                $('#clienteModalBody').html(`
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-user"></i> Información Personal</h6>
                        <hr>
                        <p><strong>Nombre:</strong> ${data.usuario.nombre} ${data.usuario.apellido}</p>
                        <p><strong>Email:</strong> ${data.usuario.email || 'N/A'}</p>
                        <p><strong>Teléfono:</strong> ${data.usuario.telefono || 'N/A'}</p>
                        <p><strong>Dirección:</strong> ${data.usuario.direccion || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-heartbeat"></i> Información Física</h6>
                        <hr>
                        <p><strong>Peso:</strong> ${data.peso ? data.peso + ' kg' : 'N/A'}</p>
                        <p><strong>Altura:</strong> ${data.altura ? data.altura + ' m' : 'N/A'}</p>
                        ${imc}
                        <p><strong>Código QR:</strong> <code>${data.codigoQR}</code></p>
                        <p><strong>Estado:</strong> 
                            <span class="badge ${data.estado === 'activo' ? 'bg-success' : 'bg-danger'}">
                                ${data.estado.charAt(0).toUpperCase() + data.estado.slice(1)}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary"><i class="fas fa-calendar"></i> Fechas</h6>
                        <hr>
                        <p><strong>Fecha de registro:</strong> ${new Date(data.created_at).toLocaleDateString('es-ES')}</p>
                        <p><strong>Última actualización:</strong> ${new Date(data.updated_at).toLocaleDateString('es-ES')}</p>
                    </div>
                </div>
            `);
            },
            error: function() {
                $('#clienteModalBody').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos del cliente
                </div>
            `);
            }
        });
    }

    function deleteCliente(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/clientes/${id}`,
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
                            xhr.responseJSON?.message || 'Error al eliminar el cliente',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endpush