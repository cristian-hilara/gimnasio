@extends('layouts.templateCliente')

@section('title','actividades')


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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .actividad-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
</div>
@endsection