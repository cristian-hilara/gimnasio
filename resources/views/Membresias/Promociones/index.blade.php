@extends('layouts.template')
@section('title', 'Promociones')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<style>
    .promo-card {
        transition: all 0.3s ease;
        border-left: 4px solid #dc3545;
        height: 100%;
    }

    .promo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .promo-vigente {
        border-left-color: #28a745;
    }

    .promo-proxima {
        border-left-color: #17a2b8;
    }

    .promo-vencida {
        border-left-color: #6c757d;
    }
</style>
@endpush

@section('content')
@include('components.success-message')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-tags"></i> Promociones</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Gestión de Promociones</li>
            </ol>
        </div>
        <div>
            <a href="{{route('membresias.index')}}" class="btn btn-success me-2">
                <i class="fas fa-id-card"></i> Ver Membresías
            </a>
            <a href="{{route('promociones.create')}}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nueva Promoción
            </a>
        </div>
    </div>

    <!-- Vista de Tarjetas -->
    <div class="row mb-4">
        @forelse($promociones as $promocion)
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card promo-card shadow-sm 
                @if($promocion->estado_vigencia['clase'] == 'bg-success') promo-vigente 
                @elseif($promocion->estado_vigencia['clase'] == 'bg-info') promo-proxima 
                @else promo-vencida @endif">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">{{$promocion->nombre}}</h5>
                        <span class="badge {{$promocion->estado_vigencia['clase']}}">
                            {{$promocion->estado_vigencia['texto']}}
                        </span>
                    </div>

                    <p class="text-muted small mb-2">
                        @if($promocion->descripcion)
                        {{Str::limit($promocion->descripcion, 80)}}
                        @else
                        Sin descripción
                        @endif
                    </p>

                    <div class="mb-3">
                        <span class="badge bg-primary">
                            <i class="fas fa-tag"></i> {{$promocion->tipo_texto}}
                        </span>
                        <span class="badge bg-secondary">
                            <i class="fas fa-id-card"></i> {{$promocion->membresias->count()}} membresías
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $promocion->fecha_inicio->format('d/m/Y') }} - {{ $promocion->fecha_fin->format('d/m/Y') }}

                        </small>
                    </div>

                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-success btn-sm"onclick="openMembresiaModal({{$promocion->id}}, '{{$promocion->nombre}}')"
                            title="Gestionar membresías">
                            <i class="fas fa-link"></i> Membresías
                        </button>
                       
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h5>No hay promociones registradas</h5>
                <p>Comienza agregando tu primera promoción</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Tabla de Promociones -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-table me-1"></i> Tabla de Promociones
        </div>
        <div class="table-responsive p-3">
            <table id="promocionesTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Vigencia</th>
                        <th>Estado</th>
                        <th>Membresías</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promociones as $promocion)
                    <tr>
                        <td>{{$promocion->id}}</td>
                        <td><strong>{{$promocion->nombre}}</strong></td>
                        <td>
                            <span class="badge bg-primary">{{$promocion->tipo_texto}}</span>
                        </td>
                        <td>
                            <small>
                                {{$promocion->fecha_inicio->format('d/m/Y')}} -
                                {{$promocion->fecha_fin->format('d/m/Y')}}
                            </small>
                        </td>
                        <td>
                            <span class="badge {{$promocion->estado_vigencia['clase']}}">
                                {{$promocion->estado_vigencia['texto']}}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{$promocion->membresias->count()}} membresías
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-success btn-sm"onclick="openMembresiaModal({{$promocion->id}}, '{{$promocion->nombre}}')"
                                    title="Gestionar membresías">
                                    <i class="fas fa-link"></i>
                                </button>
                                <button class="btn btn-info btn-sm"onclick="viewPromocion({{$promocion->id}})"
                                    title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{route('promociones.edit', $promocion->id)}}"
                                    class="btn btn-warning btn-sm"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm"onclick="deletePromocion({{$promocion->id}})"
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

<!-- Modal Ver Detalles Promoción -->
<div class="modal fade" id="viewPromocionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalles de la Promoción
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewPromocionContent">
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

<!-- Modal Gestionar Membresías de la Promoción -->
<div class="modal fade" id="membresiaModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-link"></i> Gestionar Membresías - <span id="modal_promocion_nombre"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="current_promocion_id">

                <!-- Formulario para agregar membresía -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <i class="fas fa-plus"></i> Asociar Nueva Membresía
                    </div>
                    <div class="card-body">
                        <form id="formAgregarMembresia">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Membresía *</label>
                                    <select id="membresia_id" class="form-select" required>
                                        <option value="">-- Seleccione --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Precio Promocional (Bs.) *</label>
                                    <input type="number" id="precio_promocional"
                                        class="form-control" step="0.01" min="0"
                                        placeholder="Ej: 120.00" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-save"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de membresías asociadas -->
                <div class="card">
                    <div class="card-header bg-light">
                        <i class="fas fa-list"></i> Membresías Asociadas
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="membresiasAsociadasTable">
                                <thead class="table-success">
                                    <tr>
                                        <th>ID</th>
                                        <th>Membresía</th>
                                        <th>Duración</th>
                                        <th>Precio Original</th>
                                        <th>Precio Promocional</th>
                                        <th>Descuento</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="membresiasAsociadasBody">
                                    <!-- Se llena dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Precio Promocional -->
<div class="modal fade" id="editPrecioModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i> Editar Precio Promocional
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditPrecio">
                    <input type="hidden" id="edit_membresia_id">
                    <div class="mb-3">
                        <label class="form-label">Membresía</label>
                        <input type="text" id="edit_membresia_nombre" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio Original</label>
                        <input type="text" id="edit_precio_original" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nuevo Precio Promocional (Bs.) *</label>
                        <input type="number" id="edit_precio_promocional"
                            class="form-control" step="0.01" min="0" required>
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
        $('#promocionesTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [
                [0, 'desc']
            ]
        });
    });

    // Ver detalles de promoción
    function viewPromocion(id) {
        $('#viewPromocionModal').modal('show');
        $('#viewPromocionContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `);

        $.ajax({
            url: `/promociones/${id}`,
            method: 'GET',
            success: function(data) {
                let membresiasHtml = '';
                if (data.membresias && data.membresias.length > 0) {
                    membresiasHtml = '<ul class="list-group">';
                    data.membresias.forEach(mem => {
                        const descuento = ((mem.precio - mem.pivot.precio_promocional) / mem.precio * 100).toFixed(0);
                        membresiasHtml += `
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${mem.nombre}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Precio original: <del>Bs. ${parseFloat(mem.precio).toFixed(2)}</del>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success fs-6">Bs. ${parseFloat(mem.pivot.precio_promocional).toFixed(2)}</span>
                                    <br>
                                    <small class="text-success">${descuento}% descuento</small>
                                </div>
                            </div>
                        </li>
                    `;
                    });
                    membresiasHtml += '</ul>';
                } else {
                    membresiasHtml = '<p class="text-muted">No tiene membresías asociadas</p>';
                }

                const vigenciaClass = data.vigente ? 'success' : 'danger';
                const vigenciaTexto = data.vigente ? 'Vigente' : 'No vigente';

                const formatoFecha = (iso) => {
                    const fecha = new Date(iso);
                    return new Intl.DateTimeFormat('es-BO', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    }).format(fecha);
                };
                $('#viewPromocionContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-info-circle"></i> Información General</h6>
                        <hr>
                        <p><strong>Nombre:</strong> ${data.nombre}</p>
                        <p><strong>Tipo:</strong> <span class="badge bg-primary">${data.tipo_texto}</span></p>
                        <p><strong>Descripción:</strong> ${data.descripcion || 'N/A'}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-${data.activa ? 'success' : 'secondary'}">
                                ${data.activa ? 'Activa' : 'Inactiva'}
                            </span>
                        </p>
                        <p><strong>Vigencia:</strong> 
                            <span class="badge bg-${vigenciaClass}">${vigenciaTexto}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info"><i class="fas fa-calendar"></i> Período de Vigencia</h6>
                        <hr>
                        <p><strong>Fecha inicio:</strong> ${formatoFecha(data.fecha_inicio)}</p>
                        <p><strong>Fecha fin:</strong> ${formatoFecha(data.fecha_fin)}</p>

                        
                        <h6 class="text-info mt-3"><i class="fas fa-id-card"></i> Membresías con Descuento</h6>
                        <hr>
                        ${membresiasHtml}
                    </div>
                </div>
            `);
            },
            error: function() {
                $('#viewPromocionContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos
                </div>
            `);
            }
        });
    }

    // Eliminar promoción
    function deletePromocion(id) {
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
                    url: `/promociones/${id}`,
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

    // Abrir modal de gestión de membresías
    function openMembresiaModal(promocionId, promocionNombre) {
        $('#current_promocion_id').val(promocionId);
        $('#modal_promocion_nombre').text(promocionNombre);
        $('#membresiaModal').modal('show');

        // Cargar membresías disponibles
        cargarMembresiasDisponibles(promocionId);

        // Cargar membresías asociadas
        cargarMembresiasAsociadas(promocionId);
    }

    // Cargar membresías disponibles para asociar
    function cargarMembresiasDisponibles(promocionId) {
        $.ajax({
            url: `/promociones/${promocionId}/membresias/disponibles`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">-- Seleccione --</option>';
                    response.data.forEach(mem => {
                        options += `<option value="${mem.id}" data-precio="${mem.precio}">
                        ${mem.nombre} - Bs. ${parseFloat(mem.precio).toFixed(2)} (${mem.duracion_texto})
                    </option>`;
                    });
                    $('#membresia_id').html(options);
                }
            }
        });
    }

    // Cargar membresías ya asociadas
    function cargarMembresiasAsociadas(promocionId) {
        $.ajax({
            url: `/promociones/${promocionId}/membresias`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    if (response.data.length > 0) {
                        response.data.forEach(mem => {
                            const precioOriginal = parseFloat(mem.precio);
                            const precioPromo = parseFloat(mem.pivot.precio_promocional);
                            const descuento = ((precioOriginal - precioPromo) / precioOriginal * 100).toFixed(0);

                            html += `
                            <tr id="membresia-row-${mem.id}">
                                <td>${mem.id}</td>
                                <td><strong>${mem.nombre}</strong></td>
                                <td>
                                    <span class="badge bg-info">${mem.duracion_texto}</span>
                                </td>
                                <td>
                                    <del class="text-muted">Bs. ${precioOriginal.toFixed(2)}</del>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">Bs. ${precioPromo.toFixed(2)}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">${descuento}% OFF</span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" 
                                        onclick="editPrecioPromo(${mem.id}, '${mem.nombre}', ${precioOriginal}, ${precioPromo})"
                                        title="Editar precio">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" 
                                        onclick="desvincularMembresia(${promocionId}, ${mem.id})"
                                        title="Desvincular">
                                        <i class="fas fa-unlink"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        });
                    } else {
                        html = '<tr><td colspan="7" class="text-center text-muted">No hay membresías asociadas</td></tr>';
                    }
                    $('#membresiasAsociadasBody').html(html);
                }
            }
        });
    }

    // Agregar membresía a la promoción
    $('#formAgregarMembresia').on('submit', function(e) {
        e.preventDefault();

        const promocionId = $('#current_promocion_id').val();
        const membresiaId = $('#membresia_id').val();
        const precioPromocional = $('#precio_promocional').val();

        if (!membresiaId || !precioPromocional) {
            Swal.fire('Error', 'Complete todos los campos', 'error');
            return;
        }

        $.ajax({
            url: `/promociones/${promocionId}/membresias`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                membresia_id: membresiaId,
                precio_promocional: precioPromocional
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');

                    // Limpiar formulario
                    $('#membresia_id').val('');
                    $('#precio_promocional').val('');

                    // Recargar listas
                    cargarMembresiasDisponibles(promocionId);
                    cargarMembresiasAsociadas(promocionId);
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Error al asociar', 'error');
            }
        });
    });

    // Abrir modal para editar precio promocional
    function editPrecioPromo(membresiaId, membresiaName, precioOriginal, precioActual) {
        $('#edit_membresia_id').val(membresiaId);
        $('#edit_membresia_nombre').val(membresiaName);
        $('#edit_precio_original').val('Bs. ' + precioOriginal.toFixed(2));
        $('#edit_precio_promocional').val(precioActual);
        $('#editPrecioModal').modal('show');
    }

    // Actualizar precio promocional
    $('#formEditPrecio').on('submit', function(e) {
        e.preventDefault();

        const promocionId = $('#current_promocion_id').val();
        const membresiaId = $('#edit_membresia_id').val();
        const nuevoPrecio = $('#edit_precio_promocional').val();

        $.ajax({
            url: `/promociones/${promocionId}/membresias/${membresiaId}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                precio_promocional: nuevoPrecio
            },
            success: function(response) {
                if (response.success) {
                    $('#editPrecioModal').modal('hide');
                    Swal.fire('¡Actualizado!', response.message, 'success');
                    cargarMembresiasAsociadas(promocionId);
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Error al actualizar', 'error');
            }
        });
    });

    // Desvincular membresía de la promoción
    function desvincularMembresia(promocionId, membresiaId) {
        Swal.fire({
            title: '¿Desvincular membresía?',
            text: 'Se eliminará la asociación con esta promoción',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, desvincular',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/promociones/${promocionId}/membresias/${membresiaId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('¡Desvinculado!', response.message, 'success');
                            cargarMembresiasDisponibles(promocionId);
                            cargarMembresiasAsociadas(promocionId);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Error al desvincular', 'error');
                    }
                });
            }
        });
    }

    // Auto-sugerir precio promocional (80% del precio original)
    $('#membresia_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const precioOriginal = parseFloat(selectedOption.data('precio'));

        if (precioOriginal) {
            const precioSugerido = (precioOriginal * 0.8).toFixed(2);
            $('#precio_promocional').val(precioSugerido);
        }
    });
</script>
@endpush