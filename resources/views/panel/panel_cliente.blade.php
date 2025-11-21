@extends('layouts.templateCliente')

@section('title','Panel cliente')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .calendario-grid {
        display: grid;
        grid-template-columns: 80px repeat(7, 1fr);
        gap: 2px;
        background: #dee2e6;
        border: 2px solid #dee2e6;
    }
    
    .calendario-header {
        background: #0d6efd;
        color: white;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .hora-cell {
        background: #f8f9fa;
        padding: 10px 5px;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .actividad-cell {
        background: white;
        padding: 5px;
        min-height: 60px;
        position: relative;
    }
    
    .actividad-item {
        background: linear-gradient(135deg, #038c6aff 0%, #038c6aff 100%);
        color: white;
        padding: 8px;
        border-radius: 6px;
        font-size: 0.85rem;
        margin-bottom: 3px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .actividad-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .actividad-nombre {
        font-weight: bold;
        margin-bottom: 3px;
    }
    
    .actividad-info {
        font-size: 0.75rem;
        opacity: 0.9;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .progress-circular {
        width: 120px;
        height: 120px;
        position: relative;
        margin: 0 auto;
    }
    
    .rutina-dia {
        background: #f8f9fa;
        border-left: 4px solid #0d6efd;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .calendario-grid {
            overflow-x: auto;
            display: block;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid mt-4">
    <!-- Header de Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #3703f4ff 0%, #3703f4ff 100%);">
                <div class="card-body text-white p-4">
                    <h2 class="mb-1"> Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</h2>
                    <p class="mb-0 opacity-75">{{ now()->format('l, d \d\e F \d\e Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e3f2fd;">
                    <i class="fas fa-dumbbell" style="color: #2196f3;"></i>
                </div>
                <h6 class="text-muted mb-2">Rutinas Activas</h6>
                <h3 class="mb-0">{{ $rutinaActiva ? 1 : 0 }}</h3>
                <small class="text-muted">{{ $rutinasAnteriores->count() }} anteriores</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #f3e5f5;">
                    <i class="fas fa-calendar-check" style="color: #9c27b0;"></i>
                </div>
                <h6 class="text-muted mb-2">Actividades</h6>
                <h3 class="mb-0">{{ $actividades->count() }}</h3>
                <small class="text-muted">Esta semana</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9;">
                    <i class="fas fa-id-card" style="color: #4caf50;"></i>
                </div>
                <h6 class="text-muted mb-2">Membresía</h6>
                @if($membresia)
                    <h3 class="mb-0">{{ $membresia->dias_restantes }}</h3>
                    <small class="text-muted">días restantes</small>
                @else
                    <h3 class="mb-0">-</h3>
                    <small class="text-muted">Sin membresía</small>
                @endif
            </div>
        </div>
    </div>

    <!-- Calendario de Actividades -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-3">
            <h4 class="mb-0">
                <i class="fas fa-calendar-week text-primary"></i> Calendario de Actividades
            </h4>
        </div>
        <div class="card-body">
            @if($actividades->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No hay actividades programadas actualmente.
                </div>
            @else
                @php
                    // Definir días y horas
                    $dias = [
                        'lunes' => 'Lunes',
                        'martes' => 'Martes',
                        'miercoles' => 'Miércoles',
                        'jueves' => 'Jueves',
                        'viernes' => 'Viernes',
                        'sabado' => 'Sábado',
                        'domingo' => 'Domingo'
                    ];
                    
                    // Obtener rango de horas (de 6:00 a 22:00)
                    $horaInicio = 6;
                    $horaFin = 22;
                    
                    // Agrupar actividades por día y hora
                    $actividadesPorDiaHora = [];
                    foreach($actividades as $act) {
                        $dia = strtolower($act->dia_nombre);
                        $hora = $act->hora_inicio->format('H');
                        if (!isset($actividadesPorDiaHora[$hora])) {
                            $actividadesPorDiaHora[$hora] = [];
                        }
                        if (!isset($actividadesPorDiaHora[$hora][$dia])) {
                            $actividadesPorDiaHora[$hora][$dia] = [];
                        }
                        $actividadesPorDiaHora[$hora][$dia][] = $act;
                    }
                @endphp

                <div class="table-responsive">
                    <div class="calendario-grid">
                        <!-- Header: Hora vacía + días -->
                        <div class="calendario-header"></div>
                        @foreach($dias as $diaKey => $diaNombre)
                            <div class="calendario-header">{{ $diaNombre }}</div>
                        @endforeach

                        <!-- Filas de horas -->
                        @for($hora = $horaInicio; $hora <= $horaFin; $hora++)
                            <div class="hora-cell">
                                {{ str_pad($hora, 2, '0', STR_PAD_LEFT) }}:00
                            </div>
                            
                            @foreach($dias as $diaKey => $diaNombre)
                                <div class="actividad-cell">
                                    @if(isset($actividadesPorDiaHora[$hora][$diaKey]))
                                        @foreach($actividadesPorDiaHora[$hora][$diaKey] as $act)
                                            <div class="actividad-item">
                                                <div class="actividad-nombre">
                                                    {{ $act->actividad->nombre }}
                                                </div>
                                                <div class="actividad-info">
                                                    <i class="fas fa-clock"></i> {{ $act->hora_inicio->format('H:i') }} - {{ $act->hora_fin->format('H:i') }}
                                                </div>
                                                <div class="actividad-info">
                                                    <i class="fas fa-door-open"></i> {{ $act->sala->nombre }}
                                                </div>
                                                <div class="actividad-info">
                                                    <i class="fas fa-user"></i> {{ $act->instructor->nombre_completo ?? 'Sin asignar' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Mi Membresía -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card text-success"></i> Mi Membresía
                    </h5>
                </div>
                <div class="card-body">
                    @if($membresia)
                        <div class="text-center mb-3">
                            <span class="badge {{ $membresia->estado_badge['clase'] }} fs-6 px-3 py-2">
                                {{ $membresia->estado_badge['texto'] }}
                            </span>
                        </div>

                        <div class="progress-circular mb-3">
                            <svg viewBox="0 0 36 36" class="circular-chart">
                                <path class="circle-bg"
                                    d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none"
                                    stroke="#e6e6e6"
                                    stroke-width="3"
                                />
                                <path class="circle"
                                    stroke-dasharray="{{ $membresia->porcentaje_progreso }}, 100"
                                    d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none"
                                    stroke="#4caf50"
                                    stroke-width="3"
                                />
                                <text x="18" y="20.35" class="percentage" text-anchor="middle" font-size="8" fill="#666">
                                    {{ $membresia->porcentaje_progreso }}%
                                </text>
                            </svg>
                        </div>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">
                                    <i class="fas fa-calendar-alt"></i> Inicio
                                </span>
                                <strong>{{ $membresia->fecha_inicio->format('d/m/Y') }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">
                                    <i class="fas fa-calendar-check"></i> Fin
                                </span>
                                <strong>{{ $membresia->fecha_fin->format('d/m/Y') }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">
                                    <i class="fas fa-tag"></i> Tipo
                                </span>
                                <strong>{{ ucfirst($membresia->membresia->tipo) }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">
                                    <i class="fas fa-hourglass-half"></i> Días restantes
                                </span>
                                <span class="badge bg-primary fs-6">{{ $membresia->dias_restantes }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-times-circle text-muted" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No tienes una membresía activa</p>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Obtener Membresía
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mis Rutinas -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-dumbbell text-info"></i> Mis Rutinas
                    </h5>
                    <a href="{{ route('cliente.rutinas.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list"></i> Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @if($rutinaActiva)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <span class="badge bg-success">Activa</span> 
                                    {{ $rutinaActiva->nombre }}
                                </h5>
                                <a href="{{ route('cliente.rutinas.edit', $rutinaActiva->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                            <p class="text-muted">{{ $rutinaActiva->descripcion }}</p>

                            <div class="accordion" id="rutinaAccordion">
                                @foreach($rutinaActiva->ejercicios->groupBy('pivot.dia_semana') as $dia => $ejercicios)
                                    <div class="rutina-dia">
                                        <h6 class="text-primary mb-2">
                                            <i class="fas fa-calendar-day"></i> {{ ucfirst($dia) }}
                                            <span class="badge bg-primary ms-2">{{ $ejercicios->count() }} ejercicios</span>
                                        </h6>
                                        
                                        @php
                                            $ejerciciosPorGrupo = $ejercicios->groupBy('grupo_muscular');
                                        @endphp
                                        
                                        @foreach($ejerciciosPorGrupo as $grupo => $ejsGrupo)
                                            <div class="ms-3">
                                                <small class="text-muted fw-bold">
                                                    <i class="fas fa-layer-group"></i> {{ $grupo }}
                                                </small>
                                                <ul class="mt-1 mb-2">
                                                    @foreach($ejsGrupo as $ej)
                                                        <li>
                                                            <strong>{{ $ej->nombre }}</strong> - 
                                                            {{ $ej->pivot->series }}×{{ $ej->pivot->repeticiones }}
                                                            @if($ej->pivot->peso) 
                                                                <span class="badge bg-warning text-dark">{{ $ej->pivot->peso }} kg</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-muted mb-3">
                            <i class="fas fa-history"></i> Historial de Rutinas
                        </h6>
                        @if($rutinasAnteriores->isEmpty())
                            <p class="text-muted">No hay rutinas anteriores registradas.</p>
                        @else
                            <div class="list-group">
                                @foreach($rutinasAnteriores->take(5) as $rutina)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-clipboard-list text-muted"></i>
                                            {{ $rutina->nombre }}
                                        </div>
                                        <small class="text-muted">{{ $rutina->created_at->format('d/m/Y') }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-dumbbell text-muted" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No tienes una rutina activa</p>
                            <a href="{{ route('cliente.rutinas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Rutina
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Globito de Notificaciones Mejorado -->
<div id="notification-bubble" class="notification-bubble">
    <button class="notification-close" onclick="cerrarNotificacion()">
        <i class="fas fa-times"></i>
    </button>
    <div class="notification-icon">
        <i class="fas fa-bell"></i>
    </div>
    <div class="notification-content">
        <div class="notification-title">Notificación</div>
        <div class="notification-message"></div>
    </div>
</div>

<style>
.notification-bubble {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: linear-gradient(135deg, #e81b04ff 0%, #e81b04ff 100%);
    color: white;
    padding: 1.2rem;
    border-radius: 16px;
    display: none;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    max-width: 320px;
    min-width: 280px;
    animation: slideInUp 0.5s ease-out, pulse 2s infinite;
    z-index: 9999;
    transition: all 0.3s ease;
}

.notification-bubble:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.5);
}

.notification-close {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    transition: all 0.2s;
}

.notification-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.notification-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    margin-bottom: 0.8rem;
    font-size: 1.2rem;
    animation: bellRing 1s ease-in-out infinite;
}

.notification-title {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.notification-message {
    font-size: 0.9rem;
    line-height: 1.4;
    opacity: 0.95;
}

/* Animaciones */
@keyframes slideInUp {
    from {
        transform: translateY(100px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    }
    50% {
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.6);
    }
}

@keyframes bellRing {
    0%, 100% {
        transform: rotate(0deg);
    }
    10%, 30% {
        transform: rotate(-15deg);
    }
    20%, 40% {
        transform: rotate(15deg);
    }
    50% {
        transform: rotate(0deg);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .notification-bubble {
        bottom: 20px;
        right: 20px;
        left: 20px;
        max-width: none;
        min-width: auto;
    }
}

/* Variantes de color según tipo */
.notification-bubble.success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    box-shadow: 0 8px 24px rgba(67, 233, 123, 0.4);
}

.notification-bubble.warning {
    background: linear-gradient(135deg, #e60624ff 0%, #e60624ff 100%);
    box-shadow: 0 8px 24px rgba(240, 147, 251, 0.4);
}

.notification-bubble.info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    box-shadow: 0 8px 24px rgba(79, 172, 254, 0.4);
}

.notification-bubble.danger {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    box-shadow: 0 8px 24px rgba(250, 112, 154, 0.4);
}
</style>

<script>
function mostrarNotificacion(mensaje, tipo = 'default') {
    const bubble = document.getElementById('notification-bubble');
    const messageEl = bubble.querySelector('.notification-message');
    
    // Establecer mensaje
    messageEl.textContent = mensaje;
    
    // Remover clases de tipo anteriores
    bubble.classList.remove('success', 'warning', 'info', 'danger');
    
    // Agregar clase según tipo
    if (tipo !== 'default') {
        bubble.classList.add(tipo);
    }
    
    // Mostrar notificación
    bubble.style.display = 'block';
    
    // Auto-ocultar después de 10 segundos
    setTimeout(() => {
        cerrarNotificacion();
    }, 10000);
}

function cerrarNotificacion() {
    const bubble = document.getElementById('notification-bubble');
    bubble.style.animation = 'slideInUp 0.3s ease-out reverse';
    setTimeout(() => {
        bubble.style.display = 'none';
        bubble.style.animation = 'slideInUp 0.5s ease-out, pulse 2s infinite';
    }, 300);
}

// Al cargar la página, verificar notificaciones
document.addEventListener('DOMContentLoaded', () => {
    fetch("{{ route('cliente.notificaciones') }}")
        .then(res => res.json())
        .then(data => {
            if(data.ok && data.mensaje) {
                // Determinar tipo de notificación según el mensaje o campo tipo
                let tipo = data.tipo || 'default';
                
                // Ejemplos de detección automática de tipo:
                if (data.mensaje.toLowerCase().includes('venc')) {
                    tipo = 'warning';
                } else if (data.mensaje.toLowerCase().includes('éxito') || data.mensaje.toLowerCase().includes('activ')) {
                    tipo = 'success';
                }
                
                mostrarNotificacion(data.mensaje, tipo);
            }
        })
        .catch(err => {
            console.error('Error al cargar notificaciones:', err);
        });
});

// Click en cualquier parte del bubble para cerrar
document.getElementById('notification-bubble').addEventListener('click', (e) => {
    if (!e.target.closest('.notification-close')) {
        cerrarNotificacion();
    }
});
</script>

@push('js')
@endpush