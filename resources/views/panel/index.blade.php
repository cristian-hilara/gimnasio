@extends('layouts.template')

@section('title','Panel')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@php
    use App\Models\Cliente;
    use App\Models\HistorialMembresia;
    use App\Models\ActividadHorario;
    use Carbon\Carbon;

    $rol = auth()->user()->getRoleNames()->first();

    $clientesActivos = Cliente::where('estado', 'activo')->count();

    $membresiasPorVencer = HistorialMembresia::where('estado_membresia', 'vigente')
        ->whereDate('fecha_fin', '<=', now()->addDays(7))->count();

    $membresiasVencidas = HistorialMembresia::where('estado_membresia', 'vencida')->count();

    $diaHoy = strtolower(Carbon::now()->locale('es')->dayName); // ej: 'martes'
    $actividadesHoy = ActividadHorario::where('dia_semana', $diaHoy)->count();

    $inscripcionesPorMes = HistorialMembresia::selectRaw('EXTRACT(MONTH FROM created_at) as mes, COUNT(*) as total')
        ->groupBy('mes')->orderBy('mes')->get();

    $membresiasPopulares = HistorialMembresia::with('membresia')
        ->selectRaw('membresia_id, COUNT(*) as total')
        ->groupBy('membresia_id')->get();

    $clientes = Cliente::with('usuario', 'historialMembresias')->get();
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
    <h1 class="mt-4">Panel de {{ ucfirst(strtolower($rol)) }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    {{-- Tarjetas --}}
    <div class="row">
        @php
            $cards = [
                ['color' => 'primary', 'icon' => 'fas fa-users', 'title' => 'Clientes activos', 'value' => $clientesActivos],
                ['color' => 'warning', 'icon' => 'fas fa-hourglass-half', 'title' => 'Membresías por vencer', 'value' => $membresiasPorVencer],
                ['color' => 'success', 'icon' => 'fas fa-calendar-day', 'title' => 'Actividades hoy', 'value' => $actividadesHoy],
                ['color' => 'danger', 'icon' => 'fas fa-times-circle', 'title' => 'Membresías vencidas', 'value' => $membresiasVencidas],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-xl-3 col-md-6">
            <div class="card bg-{{ $card['color'] }} text-white mb-4">
                <div class="card-body">
                    <i class="{{ $card['icon'] }}"></i> {{ $card['title'] }}
                    <h3 class="mt-2">{{ $card['value'] }}</h3>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <span>Ver detalles</span>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Gráficos --}}
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-chart-area me-1"></i> Inscripciones por mes</div>
                <div class="card-body"><canvas id="inscripcionesChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-chart-bar me-1"></i> Membresías más elegidas</div>
                <div class="card-body"><canvas id="membresiasChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Estado de clientes</div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr><th>Nombre</th><th>CI</th><th>Estado</th><th>Acción</th></tr>
                </thead>
                <tbody>
                    @foreach($clientes as $client)
                        @php
                            $membresia = $client->historialMembresias->sortByDesc('fecha_fin')->first();
                            $estado = 'Sin membresía';
                            $color = 'secondary';

                            if ($membresia && $membresia->fecha_fin) {
                                $dias = now()->diffInDays($membresia->fecha_fin, false);
                                if ($dias < 0) { $estado = 'Vencida'; $color = 'danger'; }
                                elseif ($dias <= 7) { $estado = 'Por vencer'; $color = 'warning'; }
                                else { $estado = 'Vigente'; $color = 'success'; }
                            }
                        @endphp
                        <tr>
                            <td>{{ $client->usuario->nombre }} {{ $client->usuario->apellido }}</td>
                            <td>{{ $client->usuario->ci }}</td>
                            <td><span class="badge bg-{{ $color }}">{{ $estado }}</span></td>
                            <td>
                                <a href="{{ route('inscripciones.create', ['cliente_id' => $client->id]) }}" class="btn btn-sm btn-outline-{{ $color }}">
                                    <i class="fas fa-redo"></i> Inscribir
                                </a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script>
    const inscripciones = @json($inscripcionesPorMes);
    const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    new Chart(document.getElementById('inscripcionesChart'), {
        type: 'line',
        data: {
            labels: inscripciones.map(i => meses[i.mes - 1]),
            datasets: [{
                label: 'Inscripciones',
                data: inscripciones.map(i => i.total),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                fill: true
            }]
        }
    });

    const membresias = @json($membresiasPopulares);
    new Chart(document.getElementById('membresiasChart'), {
        type: 'bar',
        data: {
            labels: membresias.map(m => m.membresia?.nombre ?? 'N/A'),
            datasets: [{
                label: 'Cantidad',
                data: membresias.map(m => m.total),
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }]
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
<script>
    new simpleDatatables.DataTable("#datatablesSimple");
</script>
@endpush
