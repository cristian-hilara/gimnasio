@extends('layouts.template')

@section('title','usuarios')

@push('css')
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')

@if (session('success'))
<script>
    let message = "{{ session('success') }}";
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: message
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4">Usuarios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Registro de usuarios</li>
    </ol>

    <div class="mb-4">
        <a href="{{route('usuarios.create')}}">
            <button type='button' class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Añadir Nuevo Usuario
            </button>
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de Usuarios
        </div>

        <div class="table-responsive p-3">
            <table id="usuariosTable" class="table table-striped table-hover align-middle shadow-sm rounded">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Rol</th>
                        <th>Fecha registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <img src="{{ $item->foto_url }}" alt="Foto de usuario" class="rounded-circle shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">



                        </td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->apellido }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->telefono }}</td>
                        <td>
                            @if($item->estado == 'activo')
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $item->getRoleNames()->first() }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($item->fecha_registro)->format('d/m/Y') }}</td>
                        @php
                        $rolActual = auth()->user()->getRoleNames()->first();
                        $rolItem = $item->getRoleNames()->first();
                        @endphp

                        <td>
                            <div class="btn-group">
                                {{-- Botón de editar --}}
                                @if($rolActual === 'ADMINISTRADOR' || ($rolActual === 'RECEPCIONISTA' && in_array($rolItem, ['CLIENTE', 'INSTRUCTOR'])))
                                <a href="{{ route('usuarios.edit', $item) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                {{-- Botón de eliminar --}}
                                @can('eliminar-usuario')
                                @if($rolActual === 'ADMINISTRADOR' || ($rolActual === 'RECEPCIONISTA' && in_array($rolItem, ['CLIENTE', 'INSTRUCTOR'])))
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $item->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de confirmación -->
                    <div class="modal fade" id="confirmModal-{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h1 class="modal-title fs-5" id="modalLabel-{{ $item->id }}">
                                        <i class="fas fa-exclamation-triangle"></i> Confirmación
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p>¿Seguro que quieres eliminar al usuario <strong>{{ $item->nombre }}</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{ route('usuarios.destroy', ['usuario' => $item->id]) }}" method="post" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
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
<script src=""></script>
<!-- Inicializar administradores-table -->

<script src="{{ asset('js/administradores.js') }}"></script>
<!-- Inicializar DataTable -->
<script>
    $(document).ready(function() {
        $('#usuariosTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    text: 'Copiar',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn btn-danger'
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    className: 'btn btn-info'
                },
                {
                    extend: 'colvis',
                    text: 'Columnas',
                    className: 'btn btn-dark'
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            }
        });
    });
</script>
@endpush