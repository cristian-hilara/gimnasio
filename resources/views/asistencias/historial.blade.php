@extends('layouts.template')
@section('title', 'Historial de Asistencias')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-history"></i> Historial de Asistencias</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('asistencias.index')}}">Asistencia</a></li>
                <li class="breadcrumb-item active">Historial</li>
            </ol>
        </div>
        <div>
            <a href="{{route('asistencias.index')}}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-filter"></i> Filtros
        </div>
        <div class="card-body">
            <form method="GET" action="{{route('asistencias.historial')}}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-calendar"></i> Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" 
                        value="{{request('fecha_inicio')}}">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-calendar"></i> Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" 
                        value="{{request('fecha_fin')}}">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-user"></i> Cliente</label>
                    <select name="cliente_id" class="form-select">
                        <option value="">Todos los clientes</option>
                        @foreach($clientes as $cliente)
                            <option value="{{$cliente->id}}" 
                                {{request('cliente_id') == $cliente->id ? 'selected' : ''}}>
                                {{$cliente->usuario->nombre}} {{$cliente->usuario->apellido}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas del período -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Asistencias
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$asistencias->total()}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Por QR
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$asistencias->where('origen', 'qr')->count()}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-qrcode fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Manual
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$asistencias->where('origen', 'manual')->count()}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-keyboard fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Promedio Diario
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{$asistencias->count() > 0 ? round($asistencias->count() / max(1, $asistencias->pluck('fecha')->unique()->count())) : 0}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <i class="fas fa-table"></i> Registro de Asistencias
        </div>
        <div class="table-responsive p-3">
            <table id="historialTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Duración</th>
                        <th>Origen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asistencias as $asistencia)
                    <tr>
                        <td>{{$asistencia->id}}</td>
                        <td>{{$asistencia->fecha->format('d/m/Y')}}</td>
                        <td>
                            <strong>{{$asistencia->cliente->usuario->nombre}} {{$asistencia->cliente->usuario->apellido}}</strong>
                            <br>
                            <small class="text-muted">{{$asistencia->cliente->usuario->email}}</small>
                        </td>
                        <td>
                            <i class="fas fa-sign-in-alt text-success"></i> 
                            {{date('H:i', strtotime($asistencia->hora_entrada))}}
                        </td>
                        <td>
                            @if($asistencia->hora_salida)
                                <i class="fas fa-sign-out-alt text-danger"></i> 
                                {{date('H:i', strtotime($asistencia->hora_salida))}}
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-circle"></i> En gimnasio
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($asistencia->duracion)
                                <span class="badge bg-info">{{$asistencia->duracion}}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            {!! $asistencia->origen_badge !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="card-footer">
            {{$asistencias->links()}}
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
$(document).ready(function() {
    $('#historialTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 25
    });
});
</script>
@endpush