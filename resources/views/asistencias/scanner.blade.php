@extends('layouts.template')
@section('title', 'Escanear QR')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
    .scanner-container {
        max-width: 600px;
        margin: 0 auto;
    }
    #reader {
        border: 3px solid #667eea;
        border-radius: 15px;
        overflow: hidden;
    }
    .resultado-card {
        border-radius: 15px;
        padding: 2rem;
        margin-top: 2rem;
        display: none;
        animation: fadeIn 0.5s ease;
    }
    .resultado-card.show {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .entrada-card {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    .salida-card {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    .error-card {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: white;
    }
    .success-icon {
        font-size: 5rem;
        animation: zoomIn 0.5s ease;
    }
    @keyframes zoomIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }
    .foto-cliente {
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
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1><i class="fas fa-qrcode"></i> Escanear Código QR</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('asistencias.index')}}">Asistencia</a></li>
                <li class="breadcrumb-item active">Scanner QR</li>
            </ol>
        </div>
        <div>
            <a href="{{route('asistencias.index')}}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="scanner-container">
        <!-- Instrucciones -->
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <h5 class="card-title">
                    <i class="fas fa-info-circle text-primary"></i> Instrucciones
                </h5>
                <p class="mb-0">
                    Coloca el código QR del cliente frente a la cámara para registrar su entrada o salida automáticamente.
                </p>
            </div>
        </div>

        <!-- Scanner -->
        <div class="card shadow-lg mb-4">
            <div class="card-body">
                <div id="reader"></div>
                <div class="text-center mt-3">
                    <button id="startButton" class="btn btn-success btn-lg" onclick="iniciarScanner()">
                        <i class="fas fa-camera"></i> Iniciar Escáner
                    </button>
                    <button id="stopButton" class="btn btn-danger btn-lg" onclick="detenerScanner()" style="display: none;">
                        <i class="fas fa-stop"></i> Detener Escáner
                    </button>
                </div>
            </div>
        </div>

        <!-- Resultado de Entrada -->
        <div id="resultadoEntrada" class="resultado-card entrada-card shadow-lg">
            <div class="text-center">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="mt-3">¡Bienvenido!</h2>
                <img id="foto_entrada" src="" alt="Foto" class="rounded-circle foto-cliente my-3">
                <h3 id="nombre_entrada"></h3>
                <hr style="border-color: rgba(255,255,255,0.3);">
                <div class="row text-start">
                    <div class="col-6">
                        <p class="mb-2"><strong><i class="fas fa-id-card"></i> Membresía:</strong></p>
                        <p id="membresia_entrada"></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-2"><strong><i class="fas fa-clock"></i> Hora entrada:</strong></p>
                        <p id="hora_entrada_entrada"></p>
                    </div>
                </div>
                <div class="alert alert-light mt-3">
                    <i class="fas fa-hourglass-half text-warning"></i> 
                    <span id="dias_restantes_entrada"></span> días restantes de membresía
                </div>
            </div>
        </div>

        <!-- Resultado de Salida -->
        <div id="resultadoSalida" class="resultado-card salida-card shadow-lg">
            <div class="text-center">
                <div class="success-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h2 class="mt-3">¡Hasta Pronto!</h2>
                <img id="foto_salida" src="" alt="Foto" class="rounded-circle foto-cliente my-3">
                <h3 id="nombre_salida"></h3>
                <hr style="border-color: rgba(255,255,255,0.3);">
                <div class="row text-start">
                    <div class="col-4">
                        <p class="mb-2"><strong><i class="fas fa-sign-in-alt"></i> Entrada:</strong></p>
                        <p id="hora_entrada_salida"></p>
                    </div>
                    <div class="col-4">
                        <p class="mb-2"><strong><i class="fas fa-sign-out-alt"></i> Salida:</strong></p>
                        <p id="hora_salida_salida"></p>
                    </div>
                    <div class="col-4">
                        <p class="mb-2"><strong><i class="fas fa-hourglass"></i> Duración:</strong></p>
                        <p id="duracion_salida"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultado de Error -->
        <div id="resultadoError" class="resultado-card error-card shadow-lg">
            <div class="text-center">
                <div class="success-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="mt-3" id="titulo_error">Error</h2>
                <p class="lead" id="mensaje_error"></p>
                <div id="info_cliente_error" style="display: none;">
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <h4 id="nombre_error"></h4>
                    <p>El cliente necesita renovar su membresía</p>
                    <a href="{{route('inscripciones.create')}}" class="btn btn-light btn-lg mt-3">
                        <i class="fas fa-plus-circle"></i> Renovar Membresía
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let html5QrcodeScanner;
let isScanning = false;

function iniciarScanner() {
    if (isScanning) return;

    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { 
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        },
        false
    );

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    
    isScanning = true;
    document.getElementById('startButton').style.display = 'none';
    document.getElementById('stopButton').style.display = 'inline-block';
    
    // Ocultar resultados previos
    ocultarResultados();
}

function detenerScanner() {
    if (!isScanning) return;

    html5QrcodeScanner.clear();
    isScanning = false;
    
    document.getElementById('startButton').style.display = 'inline-block';
    document.getElementById('stopButton').style.display = 'none';
}

function onScanSuccess(decodedText, decodedResult) {
    // Detener el scanner
    detenerScanner();
    
    // Enviar código al servidor
    registrarAsistenciaQR(decodedText);
}

function onScanFailure(error) {
    // No hacer nada, es normal que haya errores mientras se busca el QR
}

function registrarAsistenciaQR(codigoQR) {
    // Mostrar loading
    Swal.fire({
        title: 'Procesando...',
        text: 'Verificando código QR',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '{{ route("asistencias.registrarQR") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            codigo_qr: codigoQR
        },
        success: function(response) {
            Swal.close();
            
            if (response.success) {
                if (response.tipo === 'entrada') {
                    mostrarResultadoEntrada(response.cliente);
                } else {
                    mostrarResultadoSalida(response.cliente);
                }
                
                // Reproducir sonido de éxito
                reproducirSonido('success');
                
                // Auto-reiniciar scanner después de 5 segundos
                setTimeout(function() {
                    ocultarResultados();
                    iniciarScanner();
                }, 5000);
            }
        },
        error: function(xhr) {
            Swal.close();
            
            if (xhr.status === 403) {
                // Membresía vencida
                const response = xhr.responseJSON;
                mostrarResultadoError(response.message, response.cliente);
            } else if (xhr.status === 404) {
                // Código no válido
                mostrarResultadoError('Código QR no válido', null);
            } else {
                // Otro error
                mostrarResultadoError('Error al procesar el código', null);
            }
            
            // Reproducir sonido de error
            reproducirSonido('error');
            
            // Auto-reiniciar scanner después de 3 segundos
            setTimeout(function() {
                ocultarResultados();
                iniciarScanner();
            }, 3000);
        }
    });
}

function mostrarResultadoEntrada(cliente) {
    ocultarResultados();
    
    $('#nombre_entrada').text(cliente.nombre);
    $('#membresia_entrada').text(cliente.membresia);
    $('#hora_entrada_entrada').text(cliente.hora_entrada);
    $('#dias_restantes_entrada').text(cliente.dias_restantes);
    
    if (cliente.foto) {
        $('#foto_entrada').attr('src', cliente.foto).show();
    } else {
        $('#foto_entrada').hide();
    }
    
    $('#resultadoEntrada').addClass('show');
}

function mostrarResultadoSalida(cliente) {
    ocultarResultados();
    
    $('#nombre_salida').text(cliente.nombre);
    $('#hora_entrada_salida').text(cliente.hora_entrada);
    $('#hora_salida_salida').text(cliente.hora_salida);
    $('#duracion_salida').text(cliente.duracion || 'N/A');
    
    if (cliente.foto) {
        $('#foto_salida').attr('src', cliente.foto).show();
    } else {
        $('#foto_salida').hide();
    }
    
    $('#resultadoSalida').addClass('show');
}

function mostrarResultadoError(mensaje, cliente) {
    ocultarResultados();
    
    $('#mensaje_error').text(mensaje);
    
    if (cliente) {
        $('#nombre_error').text(cliente.nombre);
        $('#info_cliente_error').show();
    } else {
        $('#info_cliente_error').hide();
    }
    
    $('#resultadoError').addClass('show');
}

function ocultarResultados() {
    $('.resultado-card').removeClass('show');
}

function reproducirSonido(tipo) {
    // Crear elemento de audio
    const audio = new Audio();
    
    if (tipo === 'success') {
        // Sonido de éxito (puedes usar tu propio archivo)
        audio.src = 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYHGGm98OScTgwOUKzn77RbHAU7k9nzxnMpBSh+zPLaizsIFGS56+mhTxELTqXh8bllHgU2jdXzzHUrBSh+zPDaizsIF2e56+mhUBEKTqXh8bhlHgY2jdXzzHQrBSh+zPDaizsIF2e56+mhUBEKTqXh8bhlHgY2jdXzzHQrBSh+zPDaizsIF2e56+mhUBEK';
    } else {
        // Sonido de error
        audio.src = 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYHGGm98OScTgwOUKzn77RbHAU7k9nzxnMpBSh+zPLaizsIFGS56+mhTxELTqXh8bllHgU2jdXzzHUrBSh+zPDaizsIF2e56+mhUBEKTqXh8bhlHgY2jdXzzHQrBSh+zPDaizsIF2e56+mhUBEKTqXh8bhlHgY2jdXzzHQrBSh+zPDaizsIF2e56+mhUBEK';
    }
    
    audio.play().catch(e => console.log('No se pudo reproducir el sonido'));
}

// Iniciar scanner automáticamente al cargar la página
$(document).ready(function() {
    iniciarScanner();
});
</script>
@endpush