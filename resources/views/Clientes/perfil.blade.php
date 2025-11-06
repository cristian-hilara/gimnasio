@extends('layouts.template')
@section('title', 'Perfil del Cliente')

@push('css')
<style>
    .perfil-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        border-radius: 15px 15px 0 0;
    }
    .qr-container {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .foto-perfil {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="mt-4 mb-4">
        <a href="{{route('clientes.index')}}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow-lg">
        <div class="perfil-header text-center">
            @if($cliente->usuario->foto)
                <img src="{{asset('storage/'.$cliente->usuario->foto)}}" 
                    alt="Foto" class="rounded-circle foto-perfil mb-3">
            @else
                <div class="rounded-circle foto-perfil bg-white d-inline-flex align-items-center justify-content-center mb-3">
                    <i class="fas fa-user fa-4x text-secondary"></i>
                </div>
            @endif
            <h2>{{$cliente->usuario->nombre}} {{$cliente->usuario->apellido}}</h2>
            <p class="mb-0">
                <i class="fas fa-envelope"></i> {{$cliente->usuario->email}}
            </p>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Información del Cliente -->
                <div class="col-md-6">
                    <h5 class="mb-3">
                        <i class="fas fa-user-circle text-primary"></i> Información Personal
                    </h5>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>CI:</th>
                                <td>{{$cliente->usuario->ci ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Teléfono:</th>
                                <td>{{$cliente->usuario->telefono ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Dirección:</th>
                                <td>{{$cliente->usuario->direccion ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Peso:</th>
                                <td>{{$cliente->peso ?? 'N/A'}} kg</td>
                            </tr>
                            <tr>
                                <th>Altura:</th>
                                <td>{{$cliente->altura ?? 'N/A'}} m</td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <span class="badge {{$cliente->estado == 'activo' ? 'bg-success' : 'bg-danger'}}">
                                        {{ucfirst($cliente->estado)}}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    @if($cliente->historialMembresias->isNotEmpty())
                        @php $membresia = $cliente->historialMembresias->first(); @endphp
                        <div class="alert alert-success mt-3">
                            <h6><i class="fas fa-id-card"></i> Membresía Activa</h6>
                            <p class="mb-1"><strong>Tipo:</strong> {{$membresia->membresia->nombre}}</p>
                            <p class="mb-1"><strong>Vigencia:</strong> 
                                {{$membresia->fecha_inicio->format('d/m/Y')}} - 
                                {{$membresia->fecha_fin->format('d/m/Y')}}
                            </p>
                            <p class="mb-0">
                                <strong>Días restantes:</strong> 
                                <span class="badge bg-warning text-dark">{{$membresia->dias_restantes}} días</span>
                            </p>
                        </div>
                    @else
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Sin membresía activa
                        </div>
                    @endif
                </div>

                <!-- Código QR -->
                <div class="col-md-6">
                    <h5 class="mb-3">
                        <i class="fas fa-qrcode text-primary"></i> Código QR de Acceso
                    </h5>

                    <div class="qr-container text-center">
                        @if($cliente->codigoQR)
                            {!! QrCode::size(250)->generate($cliente->codigoQR) !!}
                            
                            <div class="mt-3">
                                <code class="bg-light p-2 d-block">{{$cliente->codigoQR}}</code>
                            </div>

                            <div class="mt-4">
                                <a href="{{route('clientes.qr.descargar', $cliente->id)}}" 
                                    class="btn btn-success me-2">
                                    <i class="fas fa-download"></i> Descargar QR
                                </a>
                                <a href="{{route('clientes.tarjeta', $cliente->id)}}" 
                                    class="btn btn-info me-2" 
                                    target="_blank">
                                    <i class="fas fa-eye"></i> Ver Tarjeta
                                </a>
                                <a href="{{route('clientes.tarjeta.pdf', $cliente->id)}}" 
                                    class="btn btn-primary">
                                    <i class="fas fa-id-card"></i> Imprimir Tarjeta
                                </a>
                            </div>

                            <div class="mt-3">
                                <form method="POST" action="{{route('clientes.qr.regenerar', $cliente->id)}}" 
                                    onsubmit="return confirm('¿Estás seguro? El código anterior dejará de funcionar.')">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-sync"></i> Regenerar Código
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                No tiene código QR generado
                            </div>
                            <form method="POST" action="{{route('clientes.qr.regenerar', $cliente->id)}}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-qrcode"></i> Generar Código QR
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-mobile-alt"></i> ¿Cómo usar el QR?</h6>
                        <ol class="mb-0 small">
                            <li>Descarga el código QR o imprime tu tarjeta</li>
                            <li>Muéstralo en el escáner al ingresar al gimnasio</li>
                            <li>El sistema registrará automáticamente tu entrada/salida</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection