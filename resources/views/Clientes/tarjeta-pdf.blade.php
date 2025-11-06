<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tarjeta de Membresía</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .tarjeta {
            width: 242.65px; /* 85.6mm */
            height: 153px;   /* 53.98mm */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 15px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .tarjeta-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .foto {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        .foto-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #667eea;
            font-size: 24px;
        }
        .nombre {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin: 8px 0;
        }
        .info {
            font-size: 9px;
            text-align: center;
            margin-bottom: 8px;
        }
        .qr-container {
            background: white;
            padding: 8px;
            border-radius: 8px;
            text-align: center;
            margin-top: 8px;
        }
        .qr-container img {
            width: 80px;
            height: 80px;
        }
        .codigo {
            font-size: 7px;
            color: #333;
            margin-top: 3px;
        }
        .membresia {
            background: rgba(255,255,255,0.9);
            color: #667eea;
            font-size: 9px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 5px;
        }
        .layout-horizontal {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .layout-horizontal .left-side {
            flex: 1;
            padding-right: 10px;
        }
        .layout-horizontal .right-side {
            flex: 0 0 90px;
        }
    </style>
</head>
<body>
    <div class="tarjeta">
        <div class="layout-horizontal">
            <div class="left-side">
                <div class="logo">
                    🏋️ MI GIMNASIO
                </div>

                <div class="tarjeta-header">
                    @if($cliente->usuario->foto)
                        <img src="{{ public_path('storage/' . $cliente->usuario->foto) }}" 
                            alt="Foto" class="foto">
                    @else
                        <div class="foto-placeholder">
                            👤
                        </div>
                    @endif
                </div>

                <div class="nombre">
                    {{strtoupper($cliente->usuario->nombre)}} {{strtoupper($cliente->usuario->apellido)}}
                </div>

                @if($membresiaVigente)
                    <div class="membresia">
                        {{$membresiaVigente->membresia->nombre}}
                    </div>
                    <div class="info">
                        Vence: {{$membresiaVigente->fecha_fin->format('d/m/Y')}}
                    </div>
                @endif

                <div class="info">
                    ID: {{$cliente->id}} | CI: {{$cliente->usuario->ci ?? 'N/A'}}
                </div>
            </div>

            <div class="right-side">
                <div class="qr-container">
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR">
                    <div class="codigo">{{$cliente->codigoQR}}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>