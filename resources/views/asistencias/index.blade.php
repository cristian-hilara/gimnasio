@extends('layouts.template')
@section('title', 'Panel de Asistencia')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<style>
    .cliente-card {
        transition: all 0.3s ease;
        border-left: 4px solid #6c757d;
    }
    .cliente-card.vigente {
        border-left-color: #28a745;
    }
    .cliente-card.vencida {
        border-left-color: #dc3545;
    }
    .cliente-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 1.5rem;
        color: white;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
    }
    .btn-entrada {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
    }
    .btn-salida {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        color: white;
    }
    .en-gimnasio {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endpush

@section('content')
@include('components.success-message')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-clipboard-check"></i> Panel de Asistencia</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Control de Acceso</li>
            </ol>
        </div>
        <div>
            <a href="{{route('asistencias.scanner')}}" class="btn btn-success btn-lg me-2">
                <i class="fas fa-qrcode"></i> Escanear QR
            </a>
            <a href="{{route('asistencias.historial')}}" class="btn btn-info">
                <i class="fas fa-history"></i> Ver Historial
            </a>
        </div>
    </div>

    <!-- Estadísticas del Día -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase mb-1" style="font-size: 0.9rem;">Asistencias Hoy</div>
                        <div class="stat-number">{{$estadisticas['total_hoy']}}</div>
                    </div>
                    <div>
                        <i class="fas fa-users fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card shadow" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase mb-1" style="font-size: 0.9rem;">En Gimnasio</div>
                        <div class="stat-number">{{$estadisticas['en_gimnasio']}}</div>
                    </div>
                    <div>
                        <i class="fas fa-dumbbell fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card shadow" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase mb-1" style="font-size: 0.9rem;">Total Clientes</div>
                        <div class="stat-number">{{$estadisticas['total_clientes']}}</div>
                    </div>
                    <div>
                        <i class="fas fa-user-friends fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card shadow" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase mb-1" style="font-size: 0.9rem;">Membresías Activas</div>
                        <div class="stat-number">{{$estadisticas['membresias_vigentes']}}</div>
                    </div>
                    <div>
                        <i class="fas fa-id-card fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{route('asistencias.index')}}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label"><i class="fas fa-search"></i> Buscar Cliente</label>
                    <input type="text" name="buscar" class="form-control" 
                        placeholder="Nombre, email, CI..." value="{{request('buscar')}}">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-filter"></i> Estado Membresía</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="vigente" {{request('estado') == 'vigente' ? 'selected' : ''}}>Vigentes</option>
                        <option value="vencida" {{request('estado') == 'vencida' ? 'selected' : ''}}>Vencidas</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{route('asistencias.index')}}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Clientes -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-list"></i> Clientes
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Foto</th>
                            <th>Cliente</th>
                            <th>Membresía</th>
                            <th>Estado</th>
                            <th>Última Asistencia</th>
                            <th>Hoy</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                            @php
                                $membresiaVigente = $cliente->historialMembresias->first();
                                $vigente = $membresiaVigente !== null;
                                $ultimaAsistencia = $cliente->asistencias->first();
                                $asistenciaHoy = $cliente->asistencias->where('fecha', today())->first();
                                $enGimnasio = $asistenciaHoy && is_null($asistenciaHoy->hora_salida);
                            @endphp
                            <tr class="cliente-card {{$vigente ? 'vigente' : 'vencida'}}">
                                <td>
                                    @if($cliente->usuario->foto)
                                        <img src="{{asset('storage/'.$cliente->usuario->foto)}}" 
                                            alt="Foto" class="rounded-circle" 
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                            style="width: 50px; height: 50px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{$cliente->usuario->nombre}} {{$cliente->usuario->apellido}}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope"></i> {{$cliente->usuario->email}}
                                    </small>
                                    @if($cliente->usuario->ci)
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card"></i> CI: {{$cliente->usuario->ci}}
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    @if($vigente)
                                        <span class="badge bg-info">{{$membresiaVigente->membresia->nombre}}</span>
                                        <br>
                                        <small class="text-muted">
                                            Vence: {{$membresiaVigente->fecha_fin->format('d/m/Y')}}
                                        </small>
                                        <br>
                                        <small class="text-warning">
                                            <i class="fas fa-hourglass-half"></i> 
                                            {{$membresiaVigente->dias_restantes}} días restantes
                                        </small>
                                    @else
                                        <span class="text-muted">Sin membresía</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vigente)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Vigente
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Vencida
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($ultimaAsistencia)
                                        <small>
                                            <i class="fas fa-calendar"></i> 
                                            {{$ultimaAsistencia->fecha->format('d/m/Y')}}
                                        </small>
                                        <br>
                                        <small>
                                            <i class="fas fa-clock"></i> 
                                            {{date('H:i', strtotime($ultimaAsistencia->hora_entrada))}}
                                        </small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($asistenciaHoy)
                                        @if($enGimnasio)
                                            <span class="badge bg-success en-gimnasio">
                                                <i class="fas fa-circle"></i> En gimnasio
                                            </span>
                                            <br>
                                            <small>Entrada: {{date('H:i', strtotime($asistenciaHoy->hora_entrada))}}</small>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-check"></i> Completado
                                            </span>
                                            <br>
                                            <small>
                                                {{date('H:i', strtotime($asistenciaHoy->hora_entrada))}} - 
                                                {{date('H:i', strtotime($asistenciaHoy->hora_salida))}}
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">Sin registro</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($vigente)
                                            @if($enGimnasio)
                                                <form method="POST" action="{{route('asistencias.registrarSalida', $cliente->id)}}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-salida btn-sm" title="Registrar salida">
                                                        <i class="fas fa-sign-out-alt"></i> Salida
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{route('asistencias.registrarManual', $cliente->id)}}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-entrada btn-sm" title="Registrar entrada">
                                                        <i class="fas fa-sign-in-alt"></i> Entrada
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{route('inscripciones.create')}}?cliente_id={{$cliente->id}}" 
                                                class="btn btn-warning btn-sm" title="Renovar membresía">
                                                <i class="fas fa-refresh"></i> Renovar
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-info btn-sm"onclick="verDetalleCliente({{$cliente->id}})" 
                                            title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle"></i> No se encontraron clientes
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-3">
                {{$clientes->links()}}
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Cliente -->
<div class="modal fade" id="detalleClienteModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle"></i> Detalle del Cliente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleClienteContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
function verDetalleCliente(clienteId) {
    $('#detalleClienteModal').modal('show');
    $('#detalleClienteContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `);

    $.ajax({
        url: `/asistencias/verificar/${clienteId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const cliente = response.cliente;
                
                let membresiaHtml = '';
                if (cliente.tiene_membresia) {
                    membresiaHtml = `
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle"></i> Membresía Activa</h6>
                            <p class="mb-1"><strong>Tipo:</strong> ${cliente.membresia.nombre}</p>
                            <p class="mb-1"><strong>Vence:</strong> ${cliente.membresia.fecha_fin}</p>
                            <p class="mb-0"><strong>Días restantes:</strong> ${cliente.membresia.dias_restantes} días</p>
                        </div>
                    `;
                } else {
                    membresiaHtml = `
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-times-circle"></i> Sin Membresía Activa</h6>
                            <p class="mb-0">El cliente necesita renovar su membresía</p>
                        </div>
                    `;
                }

                let asistenciaHtml = '';
                if (cliente.asistencia_hoy) {
                    const enGimnasio = cliente.asistencia_hoy.en_gimnasio;
                    asistenciaHtml = `
                        <div class="alert ${enGimnasio ? 'alert-success' : 'alert-secondary'}">
                            <h6><i class="fas fa-clipboard-check"></i> Asistencia de Hoy</h6>
                            <p class="mb-1"><strong>Entrada:</strong> ${cliente.asistencia_hoy.hora_entrada}</p>
                            ${enGimnasio ? 
                                '<p class="mb-0"><span class="badge bg-success">Actualmente en el gimnasio</span></p>' :
                                '<p class="mb-0">Ya registró su salida</p>'
                            }
                        </div>
                    `;
                } else {
                    asistenciaHtml = `
                        <div class="alert alert-warning">
                            <p class="mb-0"><i class="fas fa-info-circle"></i> No ha registrado asistencia hoy</p>
                        </div>
                    `;
                }

                const fotoHtml = cliente.foto ? 
                    `<img src="${cliente.foto}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">` :
                    `<div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>`;

                $('#detalleClienteContent').html(`
                    <div class="text-center">
                        ${fotoHtml}
                        <h4>${cliente.nombre}</h4>
                    </div>
                    <hr>
                    ${membresiaHtml}
                    ${asistenciaHtml}
                `);
            }
        },
        error: function() {
            $('#detalleClienteContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los datos
                </div>
            `);
        }
    });
}

// Auto-refresh cada 30 segundos para actualizar el estado "En gimnasio"
setInterval(function() {
    if (!$('.modal').is(':visible')) {
        location.reload();
    }
}, 30000);
</script>
@endpush