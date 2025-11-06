@extends('layouts.template')
@section('title', 'Tarjeta de Membresía')

@push('css')
<style>
    body {
        background: #f5f5f5;
    }
    .tarjeta-container {
        max-width: 400px;
        margin: 2rem auto;
    }
    .tarjeta {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        position: relative;
        overflow: hidden;
    }
    .tarjeta::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }
    .tarjeta-header {
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }
    .tarjeta-foto {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .tarjeta-body {
        position: relative;
        z-index: 1;
    }
    .qr-box {
        background: white;
        padding: 1rem;
        border-radius: 15px;
        text-align: center;
        margin-top: 1rem;
    }
    .logo-gym {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="text-center mt-3 mb-3">
        <a href="{{route('clientes.perfil', $cliente->id)}}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{route('clientes.tarjeta.pdf', $cliente->id)}}" class="btn btn-primary">
            <i class="fas fa-download"></i> Descargar PDF
        </a>
    </div>

    <div class="tarjeta-container">
        <div class="tarjeta">
            <div class="logo-gym">
                <i class="fas fa-dumbbell"></i> MI GIMNASIO
            </div>

            <div class="tarjeta-header">
                @if($cliente->usuario->foto)
                    <img src="{{asset('storage/'.$cliente->usuario->foto)}}" 
                        alt="Foto" class="tarjeta-foto">
                @else
                    <div class="tarjeta-foto bg-white d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-user fa-2x text-secondary"></i>
                    </div>
                @endif
            </div>

            <div class="tarjeta-body">
                <h4 class="text-center mb-3">
                    {{$cliente->usuario->nombre}} {{$cliente->usuario->apellido}}
                </h4>

                @if($membresiaVigente)
                    <div class="text-center mb-3">
                        <div class="badge bg-light text-dark fs-6 mb-2">
                            {{$membresiaVigente->membresia->nombre}}
                        </div>
                        <div class="small">
                            Vence: {{$membresiaVigente->fecha_fin->format('d/m/Y')}}
                        </div>
                    </div>
                @endif

                <div class="qr-box">
                    {!! $qrCode !!}
                    <div class="mt-2">
                        <small class="text-dark">{{$cliente->codigoQR}}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-info">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>
</div>
@endsection

@push('js')
<script>
// Ocultar botones al imprimir
window.addEventListener('beforeprint', function() {
    document.querySelectorAll('.btn').forEach(btn => btn.style.display = 'none');
});

window.addEventListener('afterprint', function() {
    document.querySelectorAll('.btn').forEach(btn => btn.style.display = '');
});
</script>
@endpush