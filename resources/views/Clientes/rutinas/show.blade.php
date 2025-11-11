@extends('layouts.templateCliente')

@section('title', $rutina->nombre)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Header de la Rutina -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-clipboard-list"></i> {{ $rutina->nombre }}
                            </h3>
                            <p class="mb-0 opacity-75">{{ $rutina->descripcion ?: 'Sin descripción' }}</p>
                        </div>
                        <div>
                            <span class="badge bg-{{ $rutina->estado == 'activa' ? 'success' : 'secondary' }} fs-6">
                                <i class="fas fa-circle-dot"></i> {{ ucfirst($rutina->estado) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <i class="fas fa-dumbbell text-primary fs-2"></i>
                                <h4 class="mb-0 mt-2">{{ $rutina->ejercicios->count() }}</h4>
                                <small class="text-muted">Ejercicios Totales</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <i class="fas fa-calendar-week text-success fs-2"></i>
                                <h4 class="mb-0 mt-2">{{ $rutina->ejercicios->groupBy('pivot.dia_semana')->count() }}</h4>
                                <small class="text-muted">Días de Entrenamiento</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <i class="fas fa-layer-group text-info fs-2"></i>
                                <h4 class="mb-0 mt-2">{{ $rutina->ejercicios->pluck('grupo_muscular')->unique()->count() }}</h4>
                                <small class="text-muted">Grupos Musculares</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <i class="fas fa-fire text-danger fs-2"></i>
                                <h4 class="mb-0 mt-2">{{ $rutina->ejercicios->sum('pivot.series') }}</h4>
                                <small class="text-muted">Series Totales</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Tabs de Visualización -->
            <ul class="nav nav-tabs mb-4" id="vistaTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="calendario-tab" data-bs-toggle="tab" 
                            data-bs-target="#calendario" type="button">
                        <i class="fas fa-calendar-week"></i> Vista Semanal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="detalles-tab" data-bs-toggle="tab" 
                            data-bs-target="#detalles" type="button">
                        <i class="fas fa-list"></i> Lista Detallada
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="grupos-tab" data-bs-toggle="tab" 
                            data-bs-target="#grupos" type="button">
                        <i class="fas fa-layer-group"></i> Por Grupo Muscular
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="vistaTabContent">
                <!-- Vista de Calendario Semanal -->
                <div class="tab-pane fade show active" id="calendario" role="tabpanel">
                    <div class="card shadow">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            @php
                                                $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                                                $diasNombres = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                                $ejerciciosPorDia = $rutina->ejercicios->groupBy('pivot.dia_semana');
                                            @endphp
                                            @foreach($diasNombres as $nombre)
                                                <th class="text-center" style="width: 14.28%">{{ $nombre }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach($diasSemana as $dia)
                                                <td class="align-top p-2" style="min-height: 200px;">
                                                    @if(isset($ejerciciosPorDia[$dia]))
                                                        @php
                                                            $ejerciciosPorGrupo = $ejerciciosPorDia[$dia]->groupBy('grupo_muscular');
                                                        @endphp
                                                        
                                                        @foreach($ejerciciosPorGrupo as $grupoMuscular => $ejerciciosGrupo)
                                                            <div class="mb-3">
                                                                <div class="badge bg-info text-dark mb-2 w-100" style="font-size: 0.75rem;">
                                                                    <i class="fas fa-layer-group"></i> {{ $grupoMuscular }}
                                                                </div>
                                                                
                                                                @foreach($ejerciciosGrupo as $ejercicio)
                                                                    <div class="card mb-2 border-primary">
                                                                        <div class="card-body p-2">
                                                                            <h6 class="card-title mb-1" style="font-size: 0.85rem;">
                                                                                <i class="fas fa-dumbbell text-primary"></i>
                                                                                {{ $ejercicio->nombre }}
                                                                            </h6>
                                                                            <div class="small">
                                                                                <span class="badge bg-primary">{{ $ejercicio->pivot->series }} series</span>
                                                                                <span class="badge bg-success">{{ $ejercicio->pivot->repeticiones }} reps</span>
                                                                                @if($ejercicio->pivot->peso)
                                                                                    <span class="badge bg-warning text-dark">{{ $ejercicio->pivot->peso }} kg</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="text-center text-muted py-4">
                                                            <i class="fas fa-bed fs-3"></i>
                                                            <p class="mb-0 mt-2 small">Descanso</p>
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
                </div>

                <!-- Vista de Lista Detallada por Día -->
                <div class="tab-pane fade" id="detalles" role="tabpanel">
                    @php
                        $ejerciciosPorDia = $rutina->ejercicios->groupBy('pivot.dia_semana');
                        $diasOrdenados = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                    @endphp

                    <div class="accordion" id="diasAccordion">
                        @foreach($diasOrdenados as $index => $dia)
                            @if(isset($ejerciciosPorDia[$dia]))
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#dia_{{ $dia }}">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <strong>{{ ucfirst($dia) }}</strong>
                                            <span class="badge bg-primary ms-2">
                                                {{ $ejerciciosPorDia[$dia]->count() }} ejercicios
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="dia_{{ $dia }}" 
                                         class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                         data-bs-parent="#diasAccordion">
                                        <div class="accordion-body">
                                            @php
                                                $ejerciciosPorGrupo = $ejerciciosPorDia[$dia]->groupBy('grupo_muscular');
                                            @endphp
                                            
                                            @foreach($ejerciciosPorGrupo as $grupo => $ejercicios)
                                                <div class="grupo-section mb-3">
                                                    <h5 class="text-primary mb-3">
                                                        <i class="fas fa-layer-group"></i> {{ $grupo }}
                                                    </h5>
                                                    
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 40%">Ejercicio</th>
                                                                    <th class="text-center">Series</th>
                                                                    <th class="text-center">Repeticiones</th>
                                                                    <th class="text-center">Peso</th>
                                                                    <th class="text-center">Volumen Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($ejercicios as $ejercicio)
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $ejercicio->nombre }}</strong>
                                                                            @if($ejercicio->descripcion)
                                                                                <br><small class="text-muted">{{ $ejercicio->descripcion }}</small>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span class="badge bg-primary">{{ $ejercicio->pivot->series }}</span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span class="badge bg-success">{{ $ejercicio->pivot->repeticiones }}</span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            @if($ejercicio->pivot->peso)
                                                                                <span class="badge bg-warning text-dark">{{ $ejercicio->pivot->peso }} kg</span>
                                                                            @else
                                                                                <span class="text-muted">-</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            @if($ejercicio->pivot->peso)
                                                                                <strong class="text-primary">
                                                                                    {{ $ejercicio->pivot->series * $ejercicio->pivot->repeticiones * $ejercicio->pivot->peso }} kg
                                                                                </strong>
                                                                            @else
                                                                                <span class="text-muted">-</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Vista por Grupo Muscular -->
                <div class="tab-pane fade" id="grupos" role="tabpanel">
                    @php
                        $ejerciciosPorGrupo = $rutina->ejercicios->groupBy('grupo_muscular');
                    @endphp

                    <div class="row">
                        @foreach($ejerciciosPorGrupo as $grupo => $ejercicios)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-layer-group"></i> {{ $grupo }}
                                        </h5>
                                        <small>{{ $ejercicios->count() }} ejercicio(s) | {{ $ejercicios->sum('pivot.series') }} series totales</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group list-group-flush">
                                            @foreach($ejercicios as $ejercicio)
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">
                                                                <i class="fas fa-dumbbell text-primary"></i>
                                                                {{ $ejercicio->nombre }}
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar-day"></i> {{ ucfirst($ejercicio->pivot->dia_semana) }}
                                                            </small>
                                                        </div>
                                                        <div class="text-end">
                                                            <div>
                                                                <span class="badge bg-primary">{{ $ejercicio->pivot->series }}x{{ $ejercicio->pivot->repeticiones }}</span>
                                                            </div>
                                                            @if($ejercicio->pivot->peso)
                                                                <div class="mt-1">
                                                                    <span class="badge bg-warning text-dark">{{ $ejercicio->pivot->peso }} kg</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="card shadow mt-4">
                <div class="card-body">
                    <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
                        <div class="d-flex gap-2">
                            <a href="{{ route('cliente.rutinas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver a Rutinas
                            </a>
                            <a href="{{ route('cliente.rutinas.edit', $rutina->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar Rutina
                            </a>
                            <button type="button" class="btn btn-info" onclick="window.print()">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                        </div>
                        <div>
                            <form action="{{ route('cliente.rutinas.destroy', $rutina->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta rutina? Esta acción no se puede deshacer.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Eliminar Rutina
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card {
        padding: 1rem;
        background: white;
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .grupo-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
    }

    .table td {
        vertical-align: middle;
    }

    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
    }

    .nav-tabs .nav-link.active {
        color: #667eea;
        font-weight: 600;
    }

    @media print {
        .btn, .nav-tabs, .card-footer {
            display: none !important;
        }
        
        .accordion-collapse {
            display: block !important;
        }
        
        .accordion-button {
            background: #f8f9fa !important;
        }
    }

    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 0.8rem;
            padding: 0.5rem;
        }
        
        .stat-card h4 {
            font-size: 1.2rem;
        }
    }
</style>
@endsection