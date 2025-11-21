@extends('layouts.templateCliente')

@section('title','Mi Perfil')

@push('css')
<style>
    .profile-container {
        max-width: 1200px;
        margin: 2rem auto;
    }

    .profile-header-card {
        background: linear-gradient(135deg, #000310ff 0%, #099a60ff 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
        margin-bottom: 2rem;
    }

    .profile-avatar-large {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: bold;
        color: #667eea;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        border: 5px solid white;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-email {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        height: 100%;
        transition: all 0.3s;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #7acecfff;
    }

    .info-card-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .info-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-label i {
        color: #667eea;
        width: 20px;
    }

    .info-value {
        font-weight: 600;
        color: #333;
        font-size: 1rem;
    }

    .imc-card {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .imc-value {
        font-size: 3.5rem;
        font-weight: bold;
        margin: 1rem 0;
    }

    .imc-status {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .imc-description {
        font-size: 0.9rem;
        color: #666;
    }

    .badge-estado {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-box {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #667eea;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-edit {
        background: linear-gradient(135deg, #000310ff 0%, #099a60ff 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    @media (max-width: 768px) {
        .profile-container {
            margin: 1rem;
        }

        .profile-avatar-large {
            width: 120px;
            height: 120px;
            font-size: 3rem;
        }

        .profile-name {
            font-size: 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid profile-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header del Perfil -->
    <div class="profile-header-card">
        <div class="profile-avatar-large">
            <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('images/default-user.png') }}"
                                alt="Foto de {{ $user->nombre }}"
                                class="rounded-circle"
                                width="205" height="205">
        </div>
        <div class="text-center">
            <h1 class="profile-name">{{ $user->nombre }} {{ $user->apellido }}</h1>
            <p class="profile-email">
                <i class="fas fa-envelope"></i> {{ $user->email }}
            </p>
            <span class="badge badge-estado bg-{{ $cliente->estado == 'activo' ? 'success' : 'secondary' }}">
                <i class="fas fa-circle" style="font-size: 0.5rem;"></i> {{ ucfirst($cliente->estado) }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información Personal -->
        <div class="col-lg-6">
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon" style="background: #e3f2fd;">
                        <i class="fas fa-user" style="color: #2196f3;"></i>
                    </div>
                    <h3 class="info-card-title">Información Personal</h3>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-signature"></i> Nombre Completo
                    </span>
                    <span class="info-value">{{ $user->nombre }} {{ $user->apellido }}</span>
                </div>

            

                

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-venus-mars"></i> Género
                    </span>
                    <span class="info-value">{{ $user->genero ? ucfirst($user->genero) : 'No especificado' }}</span>
                </div>
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="col-lg-6">
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon" style="background: #f3e5f5;">
                        <i class="fas fa-address-book" style="color: #9c27b0;"></i>
                    </div>
                    <h3 class="info-card-title">Contacto</h3>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-envelope"></i> Email
                    </span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-phone"></i> Teléfono
                    </span>
                    <span class="info-value">{{ $user->telefono ?? 'No registrado' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-map-marker-alt"></i> Dirección
                    </span>
                    <span class="info-value">{{ $user->direccion ?? 'No registrada' }}</span>
                </div>

            </div>
        </div>

        <!-- Medidas Corporales e IMC -->
        <div class="col-lg-6">
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon" style="background: #e8f5e9;">
                        <i class="fas fa-weight" style="color: #4caf50;"></i>
                    </div>
                    <h3 class="info-card-title">Medidas Corporales</h3>
                </div>

                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-value">{{ $cliente->peso ?? '-' }}</div>
                        <div class="stat-label">Peso (kg)</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value">{{ $cliente->altura ?? '-' }}</div>
                        <div class="stat-label">Altura (m)</div>
                    </div>
                </div>

                @if($cliente->peso && $cliente->altura)
                    @php
                        $imc = $cliente->peso / ($cliente->altura * $cliente->altura);
                        
                        if ($imc < 18.5) {
                            $imcStatus = 'Bajo peso';
                            $imcColor = '#ffc107';
                            $imcDesc = 'Se recomienda aumentar masa muscular';
                        } elseif ($imc >= 18.5 && $imc < 25) {
                            $imcStatus = 'Peso normal';
                            $imcColor = '#28a745';
                            $imcDesc = '¡Estás en un rango saludable!';
                        } elseif ($imc >= 25 && $imc < 30) {
                            $imcStatus = 'Sobrepeso';
                            $imcColor = '#fd7e14';
                            $imcDesc = 'Considera ejercicio cardiovascular';
                        } else {
                            $imcStatus = 'Obesidad';
                            $imcColor = '#dc3545';
                            $imcDesc = 'Consulta con un profesional';
                        }
                    @endphp

                    <div class="imc-card mt-3">
                        <h6 class="mb-0">
                            <i class="fas fa-calculator"></i> Índice de Masa Corporal
                        </h6>
                        <div class="imc-value"style="color: {{ $imcColor }}">
                            {{ number_format($imc, 1) }}
                        </div>
                        <div class="imc-status"style="color: {{ $imcColor }}">
                            <i class="fas fa-circle" style="font-size: 0.6rem;"></i> {{ $imcStatus }}
                        </div>
                        <div class="imc-description">{{ $imcDesc }}</div>
                    </div>
                @else
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        Completa tu peso y altura para calcular tu IMC
                    </div>
                @endif
            </div>
        </div>

        <!-- Estado de Membresía -->
        <div class="col-lg-6">
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon" style="background: #fff3e0;">
                        <i class="fas fa-id-card" style="color: #ff9800;"></i>
                    </div>
                    <h3 class="info-card-title">Estado del Cliente</h3>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-toggle-on"></i> Estado
                    </span>
                    <span class="badge bg-{{ $cliente->estado == 'activo' ? 'success' : 'secondary' }}">
                        {{ ucfirst($cliente->estado) }}
                    </span>
                </div>

                @if($cliente->historialMembresias()->where('estado_membresia', 'vigente')->exists())
                    @php
                        $membresia = $cliente->historialMembresias()
                            ->where('estado_membresia', 'vigente')
                            ->first();
                    @endphp

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-calendar-check"></i> Membresía
                        </span>
                        <span class="info-value">{{ $membresia->membresia->nombre ?? 'N/A' }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-hourglass-half"></i> Días Restantes
                        </span>
                        <span class="badge bg-primary">{{ $membresia->dias_restantes }} días</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-calendar-alt"></i> Vence
                        </span>
                        <span class="info-value">{{ $membresia->fecha_fin->format('d/m/Y') }}</span>
                    </div>
                @else
                    <div class="alert alert-warning mt-2">
                        <i class="fas fa-exclamation-triangle"></i> 
                        No tienes membresía activa
                    </div>
                @endif

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-dumbbell"></i> Rutinas Activas
                    </span>
                    <span class="badge bg-info">
                        {{ $cliente->rutinas()->where('estado', 'activa')->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="action-buttons">
        <a href="{{ route('cliente.perfil.edit') }}" class="btn btn-edit">
            <i class="fas fa-edit"></i> Editar Perfil
        </a>
        <a href="{{ route('cliente.rutinas.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-dumbbell"></i> Mis Rutinas
        </a>
        <a href="{{ route('cliente.panel') }}" class="btn btn-outline-secondary">
            <i class="fas fa-home"></i> Ir al Panel
        </a>
    </div>
</div>
@endsection