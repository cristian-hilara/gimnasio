@extends('layouts.template')

@section('title','Dashboard')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .stat-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin: 0.5rem 0;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
        font-weight: 500;
    }
    
    .chart-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        height: 100%;
    }
    
    .chart-card .card-header {
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 1.25rem;
        font-weight: 600;
    }
    
    .table-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .badge-status {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .quick-action-btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .quick-action-btn:hover {
        transform: scale(1.05);
    }
    
    @media (max-width: 768px) {
        .stat-value {
            font-size: 2rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@php
    use App\Models\Cliente;
    use App\Models\HistorialMembresia;
    use App\Models\ActividadHorario;
    use Carbon\Carbon;

    $rol = auth()->user()->getRoleNames()->first();

    $clientesActivos = Cliente::where('estado', 'activo')->count();
    $clientesTotales = Cliente::count();

    $membresiasPorVencer = HistorialMembresia::where('estado_membresia', 'vigente')
        ->whereDate('fecha_fin', '<=', now()->addDays(7))->count();

    $membresiasVencidas = HistorialMembresia::where('estado_membresia', 'vencida')->count();

    $diaHoy = strtolower(Carbon::now()->locale('es')->dayName);
    $actividadesHoy = ActividadHorario::where('dia_semana', $diaHoy)->count();

    $inscripcionesPorMes = HistorialMembresia::selectRaw('EXTRACT(MONTH FROM created_at) as mes, COUNT(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('mes')->orderBy('mes')->get();

    $membresiasPopulares = HistorialMembresia::with('membresia')
        ->selectRaw('membresia_id, COUNT(*) as total')
        ->groupBy('membresia_id')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

    $clientes = Cliente::with('usuario', 'historialMembresias')
        ->orderBy('created_at', 'desc')
        ->limit(100)
        ->get();

    // Ingresos del mes
    $ingresosMes = HistorialMembresia::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->with('membresia')
        ->get()
        ->sum(function($h) {
            return $h->membresia->precio ?? 0;
        });
@endphp

@section('content')

@if (session('success'))
<script>
    Swal.fire({
        title: "{{ session('success') }}",
        icon: 'success',
        showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
        hideClass: { popup: 'animate__animated animate__fadeOutDown animate__faster' }
    });
</script>
@endif

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1 class="mb-1">Dashboard {{ ucfirst(strtolower($rol)) }}</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar"></i> {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>
        <div>
            <button class="btn btn-primary quick-action-btn me-2"onclick="window.location.href='{{ route('inscripciones.create') }}'">
                <i class="fas fa-user-plus"></i> Nueva Inscripción
            </button>
            <button class="btn btn-outline-primary quick-action-btn" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
    </div>

    {{-- Tarjetas de Estadísticas --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm" style="background: linear-gradient(135deg, #7c2fc9ff 0%, #7c2fc9ff 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Clientes Activos</div>
                            <div class="stat-value">{{ $clientesActivos }}</div>
                            <small class="opacity-75">de {{ $clientesTotales }} totales</small>
                        </div>
                        <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm" style="background: linear-gradient(135deg, #f5576c 0%, #f5576c 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Por Vencer</div>
                            <div class="stat-value">{{ $membresiasPorVencer }}</div>
                            <small class="opacity-75">próximos 7 días</small>
                        </div>
                        <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm" style="background: linear-gradient(135deg, #05dde9ff 0%, #05dde9ff 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Actividades Hoy</div>
                            <div class="stat-value">{{ $actividadesHoy }}</div>
                            <small class="opacity-75">{{ ucfirst($diaHoy) }}</small>
                        </div>
                        <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm" style="background: linear-gradient(135deg, #43e97b 0%, #43e97b 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Ingresos del Mes</div>
                            <div class="stat-value">Bs {{ number_format($ingresosMes, 0) }}</div>
                            <small class="opacity-75">{{ now()->locale('es')->isoFormat('MMMM') }}</small>
                        </div>
                        <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerta de Membresías Vencidas --}}
    @if($membresiasVencidas > 0)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>¡Atención!</strong> Hay {{ $membresiasVencidas }} membresía(s) vencida(s) que requieren renovación.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Gráficos --}}
    <div class="row g-3 mb-4">
        <!-- Gráfico de Línea: Inscripciones -->
        <div class="col-xl-8">
            <div class="card chart-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            <strong>Inscripciones Mensuales {{ now()->year }}</strong>
                        </div>
                        <span class="badge bg-primary">{{ $inscripcionesPorMes->sum('total') }} total</span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="inscripcionesChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Dona: Membresías Populares -->
        <div class="col-xl-4">
            <div class="card chart-card">
                <div class="card-header">
                    <i class="fas fa-chart-pie text-success me-2"></i>
                    <strong>Membresías Más Elegidas</strong>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="membresiasChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Clientes --}}
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-2"></i>
                <strong>Estado de Clientes</strong>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('datatablesSimple').DataTable().search('').draw();">
                    <i class="fas fa-filter"></i> Limpiar Filtros
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Estado Membresía</th>
                        <th>Vencimiento</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $client)
                        @php
                            $membresia = $client->historialMembresias->sortByDesc('fecha_fin')->first();
                            $estado = 'Sin membresía';
                            $color = 'secondary';
                            $fechaVencimiento = '-';

                            if ($membresia && $membresia->fecha_fin) {
                                $fechaVencimiento = $membresia->fecha_fin->format('d/m/Y');
                                $dias = now()->diffInDays($membresia->fecha_fin, false);
                                
                                if ($dias < 0) { 
                                    $estado = 'Vencida'; 
                                    $color = 'danger'; 
                                }
                                elseif ($dias <= 7) { 
                                    $estado = 'Por vencer (' . $dias . ' días)'; 
                                    $color = 'warning'; 
                                }
                                else { 
                                    $estado = 'Vigente (' . $dias . ' días)'; 
                                    $color = 'success'; 
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                        {{ substr($client->usuario->nombre, 0, 1) }}{{ substr($client->usuario->apellido, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $client->usuario->nombre }} {{ $client->usuario->apellido }}</div>
                                        <small class="text-muted">{{ $client->usuario->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $client->usuario->ci }}</td>
                            <td>
                                <span class="badge bg-{{ $color }} badge-status">
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i> {{ $estado }}
                                </span>
                            </td>
                            <td>{{ $fechaVencimiento }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('inscripciones.create', ['cliente_id' => $client->id]) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Inscribir/Renovar">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                    <a href="{{ route('clientes.show', $client->id) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $client->id) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Configuración global de Chart.js
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#666';

    // Gráfico de Inscripciones (Línea con área)
    const inscripciones = @json($inscripcionesPorMes);
    const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    
    const ctxLinea = document.getElementById('inscripcionesChart');
    new Chart(ctxLinea, {
        type: 'line',
        data: {
            labels: inscripciones.map(i => meses[i.mes - 1]),
            datasets: [{
                label: 'Inscripciones',
                data: inscripciones.map(i => i.total),
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(102, 126, 234, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gráfico de Membresías (Dona)
    const membresias = @json($membresiasPopulares);
    const colores = [
        'rgba(102, 126, 234, 0.8)',
        'rgba(118, 75, 162, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(75, 192, 192, 0.8)'
    ];
    
    const ctxDona = document.getElementById('membresiasChart');
    new Chart(ctxDona, {
        type: 'doughnut',
        data: {
            labels: membresias.map(m => m.membresia?.nombre ?? 'N/A'),
            datasets: [{
                data: membresias.map(m => m.total),
                backgroundColor: colores,
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
<script>
    const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        labels: {
            placeholder: "Buscar cliente...",
            perPage: "registros por página",
            noRows: "No se encontraron resultados",
            info: "Mostrando {start} a {end} de {rows} registros"
        }
    });
</script>
@endpush