@extends('layouts.templateCliente')

@section('title','Chat Personal del Cliente')

@section('content')
<div class="container mt-4">
    <h3>🤖 Tu Asistente Personal</h3>
    <div id="chat-box" class="border p-3 mb-3" style="height:400px; overflow-y:auto;">
        <div class="text-muted">El asistente está listo para ayudarte...</div>
    </div>

    <form id="chat-form">
        @csrf
        <div class="input-group">
            <input type="text" name="message" id="message" class="form-control" placeholder="Escribe tu mensaje...">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>
</div>

<!-- Globito flotante persistente -->
<div id="notification-bubble"
     style="position:fixed; bottom:80px; right:20px; background:#007bff; color:#fff;
            padding:10px 14px; border-radius:20px; display:none; cursor:pointer;
            box-shadow:0 4px 6px rgba(0,0,0,0.2); max-width:280px;">
</div>

<script>
document.getElementById('chat-form').addEventListener('submit', function(e){
    e.preventDefault();
    let mensaje = document.getElementById('message').value;
    if(!mensaje.trim()) return;

    let chatBox = document.getElementById('chat-box');
    chatBox.innerHTML += `<div class="text-end"><strong>Tú:</strong> ${mensaje}</div>`;

    fetch("{{ route('cliente.chat.responder') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({message: mensaje})
    })
    .then(res => res.json())
    .then(data => {
        if(data.ok){
            // data.reply es un array de mensajes
            data.reply.forEach((msg, i) => {
                setTimeout(() => {
                    chatBox.innerHTML += `<div><strong>Asistente:</strong> ${msg.trim()}</div>`;
                    chatBox.scrollTop = chatBox.scrollHeight;
                }, i * 900);
            });
        } else {
            chatBox.innerHTML += `<div class="text-danger"><strong>Error:</strong> ${data.error}</div>`;
        }
    })
    .catch(err => {
        chatBox.innerHTML += `<div class="text-danger"><strong>Error de conexión:</strong> ${err}</div>`;
    });

    document.getElementById('message').value = '';
});

// Notificación: mostrar hasta que el cliente haga click
function mostrarNotificacion(mensaje){
    const bubble = document.getElementById('notification-bubble');
    bubble.textContent = mensaje;
    bubble.style.display = 'block';
    bubble.onclick = () => bubble.style.display = 'none';
}

// Al cargar la página, pedir notificaciones
document.addEventListener('DOMContentLoaded', () => {
    fetch("{{ route('cliente.notificaciones') }}")
        .then(res => res.json())
        .then(data => {
            if(data.ok && data.mensaje){
                mostrarNotificacion(data.mensaje);
            }
        })
        .catch(() => {});
});
</script>
@endsection
