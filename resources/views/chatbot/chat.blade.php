<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistente Virtual - Gimnasio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .message-animation {
            animation: fadeIn 0.3s ease-out;
        }
        .gradient-gym {
            background: linear-gradient(135deg, #10bb04ff 0%, #050112ff 100%);
        }
        .gradient-user {
            background: linear-gradient(135deg, #1303efff 0%, #1b05e2ff 100%);
        }
        .gradient-bot {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .gym-pattern {
            background-color: #0f172a;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.03) 35px, rgba(255,255,255,.03) 70px);
        }
    </style>
</head>
<body class="gym-pattern min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-4xl">
        <!-- Header del Chat -->
        <div class="gradient-gym rounded-t-3xl p-6 shadow-2xl">
            <div class="flex items-center space-x-4">
                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-white">Asistente Virtual GYM</h1>
                    <p class="text-white/80 text-sm">Tu entrenador personal disponible 24/7</p>
                </div>
                <div class="bg-green-400 w-3 h-3 rounded-full animate-pulse"></div>
            </div>
        </div>

        <!-- Área de Conversación -->
        <div class="bg-slate-800 shadow-2xl" style="height: 500px;">
            <div id="conversation" class="h-full overflow-y-auto p-6 space-y-4">
                <!-- Mensaje de bienvenida -->
                <div class="flex items-start space-x-3 message-animation">
                    <div class="gradient-bot p-2 rounded-full flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-700 rounded-2xl rounded-tl-sm p-4 shadow-lg">
                            <p class="text-white text-sm">¡Hola Mole! 💪 Soy tu asistente virtual del gimnasio. Estoy aquí para ayudarte con tus consultas sobre horarios, clases, membresías y más. ¿En qué puedo ayudarte hoy?</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Entrada -->
        <div class="bg-slate-900 rounded-b-3xl p-6 shadow-2xl">
            <form id="chatForm" class="flex items-center space-x-3">
                @csrf
                <div class="flex-1 relative">
                    <input 
                        id="message" 
                        name="message" 
                        type="text" 
                        class="w-full bg-slate-700 text-white rounded-full px-6 py-4 pr-12 focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder-slate-400" 
                        placeholder="Escribe tu consulta aquí..." 
                        autocomplete="off"
                    />
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                </div>
                <button 
                    type="submit" 
                    class="gradient-user text-white px-8 py-4 rounded-full font-semibold hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center space-x-2"
                >
                    <span>Enviar</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
            <p class="text-slate-500 text-xs mt-3 text-center">
                Presiona Enter para enviar • Powered by Gemini AI
            </p>
        </div>
    </div>

    <script>
        const form = document.getElementById('chatForm');
        const conv = document.getElementById('conversation');
        const messageInput = document.getElementById('message');

        // Permitir envío con Enter
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = messageInput.value.trim();
            if (!msg) return;

            // Agregar mensaje del usuario
            appendMessage(msg, 'user');
            messageInput.value = '';

            // Mostrar indicador de escritura
            const typingId = showTypingIndicator();

            try {
                const res = await fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: msg })
                });

                const data = await res.json();
                
                // Remover indicador de escritura
                removeTypingIndicator(typingId);

                if (data.ok) {
                    appendMessage(data.reply, 'bot');
                } else {
                    appendMessage("Lo siento, ocurrió un error. Por favor intenta de nuevo.", 'error');
                }
            } catch (error) {
                removeTypingIndicator(typingId);
                appendMessage("Error de conexión. Verifica tu internet e intenta nuevamente.", 'error');
            }
        });

        function appendMessage(text, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-3 message-animation';
            
            if (type === 'user') {
                messageDiv.classList.add('flex-row-reverse', 'space-x-reverse');
                messageDiv.innerHTML = `
                    <div class="gradient-user p-2 rounded-full flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 flex justify-end">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl rounded-tr-sm p-4 shadow-lg max-w-md">
                            <p class="text-white text-sm">${escapeHtml(text)}</p>
                        </div>
                    </div>
                `;
            } else if (type === 'bot') {
                messageDiv.innerHTML = `
                    <div class="gradient-bot p-2 rounded-full flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-700 rounded-2xl rounded-tl-sm p-4 shadow-lg max-w-md">
                            <p class="text-white text-sm">${escapeHtml(text)}</p>
                        </div>
                    </div>
                `;
            } else if (type === 'error') {
                messageDiv.innerHTML = `
                    <div class="bg-red-500 p-2 rounded-full flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="bg-red-900/50 rounded-2xl rounded-tl-sm p-4 shadow-lg max-w-md border border-red-500">
                            <p class="text-red-200 text-sm">${escapeHtml(text)}</p>
                        </div>
                    </div>
                `;
            }
            
            conv.appendChild(messageDiv);
            conv.scrollTop = conv.scrollHeight;
        }

        function showTypingIndicator() {
            const id = 'typing-' + Date.now();
            const typingDiv = document.createElement('div');
            typingDiv.id = id;
            typingDiv.className = 'flex items-start space-x-3 message-animation';
            typingDiv.innerHTML = `
                <div class="gradient-bot p-2 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="bg-slate-700 rounded-2xl rounded-tl-sm p-4 shadow-lg">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>
            `;
            conv.appendChild(typingDiv);
            conv.scrollTop = conv.scrollHeight;
            return id;
        }

        function removeTypingIndicator(id) {
            const element = document.getElementById(id);
            if (element) {
                element.remove();
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>