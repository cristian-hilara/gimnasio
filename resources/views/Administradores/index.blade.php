@extends('layouts.template')
@section('title','Administradores')

@push('css')
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')

<!--Aqui esta el mensaje de exito papus-->
@include('components.success-message')

<div class="container-fluid px-4">
    <h1 class="mt-4">Administrador</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Registro de Administradores</li>
    </ol>

    <div class="mb-4">
        <a href="{{route('administrador.create')}}" class="btn btn-primary">
            <button type='button' class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Añadir Nuevo Administrador
            </button>
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de Administrador
        </div>

        <div class="table-responsive p-3">
            <table id="usuariosTable" class="table table-striped table-hover align-middle shadow-sm rounded">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Area</th>
                        <th>Estado</th>
                        <th>Fecha registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admin as $adminis)
                    <tr>
                        <td>{{ $adminis->id }}</td>

                        <td><span>{{$adminis->usuario->nombre}} {{$adminis->usuario->apellido}}</span></td>

                        <td>{{ $adminis->area_responsabilidad}}</td>
                        <td>
                            @if($adminis->estado == 'activo')
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($adminis->fecha_registro)->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('administrador.edit',$adminis->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @can('eliminar-usuario')
                                <button class="btn btn-outline-danger btn-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $adminis->id }}" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de confirmación -->
                    <div class="modal fade" id="confirmModal-{{ $adminis->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $adminis->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-gradient text-white" style="background: linear-gradient(90deg, #ff5858 0%, #f09819 100%);">
                                    <h5 class="modal-title" id="modalLabel-{{ $adminis->id }}">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Confirmar eliminación
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p class="mb-3">¿Seguro que quieres eliminar al administrador <strong>{{ $adminis->usuario->nombre }} {{$adminis->usuario->apellido}}</strong>?</p>
                                    <i class="fas fa-user-slash fa-3x text-danger mb-3"></i>
                                </div>
                                <div class="modal-footer justify-content-center border-0">
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{ route('administrador.eliminar', $adminis->id) }}" method="post" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger px-4">Confirmar</button>
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

<!-- Inicializar DataTable -->
<script src="{{asset('js/administradores-table.js')}}"></script>
@endpush