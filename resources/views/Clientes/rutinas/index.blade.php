@extends('layouts.templateCliente')

@section('title','Mis Rutinas')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Rutinas</h2>
        <a href="{{ route('cliente.rutinas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Rutina
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabs para alternar entre vista de tarjetas y calendario -->
    <ul class="nav nav-tabs mb-4" id="rutinaTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="calendario-tab" data-bs-toggle="tab" data-bs-target="#calendario" type="button">
                <i class="fas fa-calendar-week"></i> Calendario Semanal
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tarjetas-tab" data-bs-toggle="tab" data-bs-target="#tarjetas" type="button">
                <i class="fas fa-th-large"></i> Vista de Tarjetas
            </button>
        </li>
    </ul>

    <div class="tab-content" id="rutinaTabContent">
        <!-- Vista de Calendario Semanal -->
        <div class="tab-pane fade show active" id="calendario" role="tabpanel">
            @if($rutinas->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No tienes rutinas creadas. ¡Comienza creando tu primera rutina!
                </div>
            @else
                @foreach($rutinas as $rutina)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">{{ $rutina->nombre }}</h5>
                                <small>{{ $rutina->descripcion }}</small>
                            </div>
                            <div>
                                <span class="badge bg-{{ $rutina->estado == 'activa' ? 'success' : 'secondary' }} me-2">
                                    {{ ucfirst($rutina->estado) }}
                                </span>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light" title="Ver detalles"
                                            data-bs-toggle="modal" data-bs-target="#modalRutina{{ $rutina->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('cliente.rutinas.edit',$rutina->id) }}" class="btn btn-sm btn-light" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('cliente.rutinas.destroy',$rutina->id) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta rutina?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 14.28%">Lunes</th>
                                            <th class="text-center" style="width: 14.28%">Martes</th>
                                            <th class="text-center" style="width: 14.28%">Miércoles</th>
                                            <th class="text-center" style="width: 14.28%">Jueves</th>
                                            <th class="text-center" style="width: 14.28%">Viernes</th>
                                            <th class="text-center" style="width: 14.28%">Sábado</th>
                                            <th class="text-center" style="width: 14.28%">Domingo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @php
                                                $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                                                // Agrupar ejercicios por día
                                                $ejerciciosPorDia = $rutina->ejercicios->groupBy('pivot.dia_semana');
                                            @endphp
                                            
                                            @foreach($diasSemana as $dia)
                                                <td class="align-top p-2" style="min-height: 150px;">
                                                    @if(isset($ejerciciosPorDia[$dia]))
                                                        @php
                                                            // Agrupar ejercicios del día por grupo muscular
                                                            $ejerciciosPorGrupo = $ejerciciosPorDia[$dia]->groupBy('grupo_muscular');
                                                        @endphp
                                                        
                                                        @foreach($ejerciciosPorGrupo as $grupoMuscular => $ejerciciosGrupo)
                                                            <div class="mb-3">
                                                                <!-- Encabezado del grupo muscular -->
                                                                <div class="badge bg-info text-dark mb-2 w-100" style="font-size: 0.75rem;">
                                                                    <i class="fas fa-layer-group"></i> {{ $grupoMuscular }}
                                                                </div>
                                                                
                                                                <!-- Ejercicios del grupo -->
                                                                @foreach($ejerciciosGrupo as $ejercicio)
                                                                    <div class="card mb-2 border-primary">
                                                                        <div class="card-body p-2">
                                                                            <h6 class="card-title mb-1" style="font-size: 0.85rem;">
                                                                                <i class="fas fa-dumbbell text-primary"></i>
                                                                                {{ $ejercicio->nombre }}
                                                                            </h6>
                                                                            <div class="small text-muted">
                                                                                <div><strong>Series:</strong> {{ $ejercicio->pivot->series }}</div>
                                                                                <div><strong>Reps:</strong> {{ $ejercicio->pivot->repeticiones }}</div>
                                                                                @if($ejercicio->pivot->peso)
                                                                                    <div><strong>Peso:</strong> {{ $ejercicio->pivot->peso }} kg</div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="text-center text-muted py-3">
                                                            <i class="fas fa-moon"></i>
                                                            <small class="d-block">Descanso</small>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Vista de Tarjetas (Original mejorada) -->
        <div class="tab-pane fade" id="tarjetas" role="tabpanel">
            @if($rutinas->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No tienes rutinas creadas. ¡Comienza creando tu primera rutina!
                </div>
            @else
                <div class="row">
                    @foreach($rutinas as $rutina)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-clipboard-list text-primary"></i>
                                        {{ $rutina->nombre }}
                                    </h5>
                                    <p class="card-text text-muted">{{ $rutina->descripcion }}</p>
                                    
                                    <div class="mb-3">
                                        <span class="badge bg-{{ $rutina->estado == 'activa' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($rutina->estado) }}
                                        </span>
                                        <span class="badge bg-info">
                                            {{ $rutina->ejercicios->count() }} ejercicios
                                        </span>
                                    </div>

                                    @if($rutina->instructor)
                                        <p class="small text-muted mb-3">
                                            <i class="fas fa-user-tie"></i> 
                                            Instructor: {{ $rutina->instructor->nombre ?? 'Sin asignar' }}
                                        </p>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-sm btn-info"
                                                data-bs-toggle="modal" data-bs-target="#modalRutina{{ $rutina->id }}">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </button>
                                        <div class="btn-group">
                                            <a href="{{ route('cliente.rutinas.edit',$rutina->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <form action="{{ route('cliente.rutinas.destroy',$rutina->id) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta rutina?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modales para Ver Detalles de Rutinas -->
@foreach($rutinas as $rutina)
<div class="modal fade" id="modalRutina{{ $rutina->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $rutina->id }}">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel{{ $rutina->id }}">
                    <i class="fas fa-clipboard-list"></i> {{ $rutina->nombre }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Información Básica -->
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Descripción:</h6>
                    <p>{{ $rutina->descripcion ?: 'Sin descripción' }}</p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Estado:</h6>
                        <span class="badge bg-{{ $rutina->estado == 'activa' ? 'success' : 'secondary' }}">
                            {{ ucfirst($rutina->estado) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Total de Ejercicios:</h6>
                        <span class="badge bg-info">{{ $rutina->ejercicios->count() }} ejercicios</span>
                    </div>
                </div>

                <hr>

                <!-- Ejercicios por Día -->
                <h6 class="text-primary mb-3">
                    <i class="fas fa-dumbbell"></i> Ejercicios Semanales
                </h6>

                @php
                    $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                    $diasNombres = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    $ejerciciosPorDia = $rutina->ejercicios->groupBy('pivot.dia_semana');
                @endphp

                @foreach($diasSemana as $index => $dia)
                    @if(isset($ejerciciosPorDia[$dia]))
                        <div class="mb-3">
                            <h6 class="bg-light p-2 rounded">
                                <i class="fas fa-calendar-day text-primary"></i> {{ $diasNombres[$index] }}
                            </h6>
                            
                            @php
                                $ejerciciosPorGrupo = $ejerciciosPorDia[$dia]->groupBy('grupo_muscular');
                            @endphp
                            
                            @foreach($ejerciciosPorGrupo as $grupo => $ejercicios)
                                <div class="ms-3 mb-2">
                                    <small class="text-muted fw-bold">
                                        <i class="fas fa-layer-group"></i> {{ $grupo }}
                                    </small>
                                    <ul class="list-unstyled ms-3 mt-1">
                                        @foreach($ejercicios as $ejercicio)
                                            <li class="mb-2">
                                                <i class="fas fa-dumbbell text-primary"></i>
                                                <strong>{{ $ejercicio->nombre }}</strong>
                                                <br>
                                                <small class="text-muted ms-3">
                                                    {{ $ejercicio->pivot->series }} series × {{ $ejercicio->pivot->repeticiones }} reps
                                                    @if($ejercicio->pivot->peso)
                                                        • {{ $ejercicio->pivot->peso }} kg
                                                    @endif
                                                </small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

                @if($rutina->ejercicios->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Esta rutina no tiene ejercicios asignados.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <a href="{{ route('cliente.rutinas.edit', $rutina->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    .table td {
        vertical-align: top;
    }
    
    .card-title {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .nav-tabs .nav-link {
        color: #495057;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        font-weight: 600;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 0.8rem;
            padding: 0.5rem;
        }
    }
</style>

<!-- Modales para Ver Detalles de Rutinas -->
@foreach($rutinas as $rutina)
<div class="modal fade" id="modalRutina{{ $rutina->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $rutina->id }}">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel{{ $rutina->id }}">
                    <i class="fas fa-clipboard-list"></i> {{ $rutina->nombre }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Información Básica -->
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Descripción:</h6>
                    <p>{{ $rutina->descripcion ?: 'Sin descripción' }}</p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Estado:</h6>
                        <span class="badge bg-{{ $rutina->estado == 'activa' ? 'success' : 'secondary' }}">
                            {{ ucfirst($rutina->estado) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Total de Ejercicios:</h6>
                        <span class="badge bg-info">{{ $rutina->ejercicios->count() }} ejercicios</span>
                    </div>
                </div>

                <hr>

                <!-- Ejercicios por Día -->
                <h6 class="text-primary mb-3">
                    <i class="fas fa-dumbbell"></i> Ejercicios Semanales
                </h6>

                @php
                    $diasSemanaModal = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                    $diasNombresModal = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    $ejerciciosPorDiaModal = $rutina->ejercicios->groupBy('pivot.dia_semana');
                @endphp

                @foreach($diasSemanaModal as $index => $dia)
                    @if(isset($ejerciciosPorDiaModal[$dia]))
                        <div class="mb-3">
                            <h6 class="bg-light p-2 rounded">
                                <i class="fas fa-calendar-day text-primary"></i> {{ $diasNombresModal[$index] }}
                            </h6>
                            
                            @php
                                $ejerciciosPorGrupoModal = $ejerciciosPorDiaModal[$dia]->groupBy('grupo_muscular');
                            @endphp
                            
                            @foreach($ejerciciosPorGrupoModal as $grupo => $ejercicios)
                                <div class="ms-3 mb-2">
                                    <small class="text-muted fw-bold">
                                        <i class="fas fa-layer-group"></i> {{ $grupo }}
                                    </small>
                                    <ul class="list-unstyled ms-3 mt-1">
                                        @foreach($ejercicios as $ejercicio)
                                            <li class="mb-2">
                                                <i class="fas fa-dumbbell text-primary"></i>
                                                <strong>{{ $ejercicio->nombre }}</strong>
                                                <br>
                                                <small class="text-muted ms-3">
                                                    {{ $ejercicio->pivot->series }} series × {{ $ejercicio->pivot->repeticiones }} reps
                                                    @if($ejercicio->pivot->peso)
                                                        • {{ $ejercicio->pivot->peso }} kg
                                                    @endif
                                                </small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

                @if($rutina->ejercicios->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Esta rutina no tiene ejercicios asignados.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <a href="{{ route('cliente.rutinas.edit', $rutina->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection