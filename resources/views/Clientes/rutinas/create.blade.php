@extends('layouts.templateCliente')

@section('title','Crear Rutina')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle"></i> Crear Nueva Rutina
                    </h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>¡Errores!</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('cliente.rutinas.store') }}" method="POST" id="rutinaForm">
                        @csrf

                        <!-- Información Básica -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información Básica</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Nombre de la Rutina <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre" class="form-control" 
                                               placeholder="Ej: Rutina de Volumen" 
                                               value="{{ old('nombre') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="2" 
                                              placeholder="Describe el objetivo de tu rutina...">{{ old('descripcion') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tipo de Rutina <span class="text-danger">*</span></label>
                                    <select name="tipo" class="form-select" id="tipoRutina" required>
                                        <option value="">Selecciona un tipo</option>
                                        <option value="personalizada">Personalizada (Elige tus ejercicios)</option>
                                        <option value="weider">Weider (Generación Automática)</option>
                                        <option value="push_pull_legs">Push Pull Legs (Generación Automática)</option>
                                        <option value="full_body">Full Body (Generación Automática)</option>
                                        <option value="femenina">Rutina Femenina (Generación Automática)</option>
                                    </select>
                                </div>

                                <!-- Mensaje informativo según tipo -->
                                <div id="tipoInfo" class="alert d-none"></div>
                            </div>
                        </div>

                        <!-- Rutina Personalizada por Días -->
                        <div class="card mb-4 d-none" id="rutinaPersonalizadaCard">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-calendar-week"></i> Planificación Semanal</h5>
                                <small class="text-muted">Configura cada día de tu rutina</small>
                            </div>
                            <div class="card-body">
                                @php
                                    $diasSemana = [
                                        'lunes' => 'Lunes',
                                        'martes' => 'Martes',
                                        'miercoles' => 'Miércoles',
                                        'jueves' => 'Jueves',
                                        'viernes' => 'Viernes',
                                        'sabado' => 'Sábado',
                                        'domingo' => 'Domingo'
                                    ];
                                    
                                    // Obtener grupos musculares únicos
                                    $gruposMusculares = \App\Models\Ejercicio::select('grupo_muscular')
                                        ->distinct()
                                        ->orderBy('grupo_muscular')
                                        ->pluck('grupo_muscular');
                                @endphp

                                <div class="accordion" id="diasAccordion">
                                    @foreach($diasSemana as $diaKey => $diaNombre)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#dia_{{ $diaKey }}"
                                                        aria-expanded="false">
                                                    <i class="fas fa-calendar-day me-2"></i> {{ $diaNombre }}
                                                    <span class="badge bg-secondary ms-2 ejercicios-count-{{ $diaKey }}">0 ejercicios</span>
                                                </button>
                                            </h2>
                                            <div id="dia_{{ $diaKey }}" class="accordion-collapse collapse" 
                                                 data-bs-parent="#diasAccordion">
                                                <div class="accordion-body">
                                                    <!-- Selector de grupos musculares -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">
                                                            <i class="fas fa-layer-group"></i> 
                                                            Selecciona Grupos Musculares (máximo 3)
                                                        </label>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach($gruposMusculares as $grupo)
                                                                <div class="form-check">
                                                                    <input class="form-check-input grupo-muscular-check" 
                                                                           type="checkbox" 
                                                                           value="{{ $grupo }}"
                                                                           data-dia="{{ $diaKey }}"
                                                                           id="grupo_{{ $diaKey }}_{{ $loop->index }}">
                                                                    <label class="form-check-label" 
                                                                           for="grupo_{{ $diaKey }}_{{ $loop->index }}">
                                                                        {{ $grupo }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <small class="text-muted">Selecciona los grupos musculares que trabajarás este día</small>
                                                    </div>

                                                    <hr>

                                                    <!-- Contenedor de ejercicios del día -->
                                                    <div class="ejercicios-dia-container" data-dia="{{ $diaKey }}">
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle"></i> 
                                                            Selecciona grupos musculares arriba para ver los ejercicios disponibles
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success" id="btnSubmit">
                                <i class="fas fa-save"></i> Guardar Rutina
                            </button>
                            <a href="{{ route('cliente.rutinas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ejercicio-card {
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
    }
    
    .ejercicio-card:hover {
        box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .grupo-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>

<script>
// Datos de ejercicios desde el backend
const ejerciciosPorGrupo = @json($ejercicios->groupBy('grupo_muscular'));

document.addEventListener('DOMContentLoaded', function() {
    const tipoRutina = document.getElementById('tipoRutina');
    const rutinaPersonalizadaCard = document.getElementById('rutinaPersonalizadaCard');
    const tipoInfo = document.getElementById('tipoInfo');

    // Información de cada tipo de rutina
    const infoRutinas = {
        'weider': {
            titulo: ' Rutina Weider',
            descripcion: 'Se generará automáticamente con un día dedicado a cada grupo muscular principal.',
            clase: 'alert-info'
        },
        'push_pull_legs': {
            titulo: ' Push Pull Legs',
            descripcion: 'Se generará automáticamente: Lunes (Push/Empuje), Miércoles (Pull/Jalón), Viernes (Legs/Piernas).',
            clase: 'alert-info'
        },
        'full_body': {
            titulo: ' Full Body',
            descripcion: 'Se generará automáticamente trabajando todo el cuerpo en cada sesión.',
            clase: 'alert-info'
        },
        'femenina': {
            titulo: ' Rutina Femenina',
            descripcion: 'Se generará automáticamente con enfoque en glúteos, piernas y core.',
            clase: 'alert-info'
        },
        'personalizada': {
            titulo: ' Rutina Personalizada',
            descripcion: 'Selecciona los grupos musculares para cada día y elige tus ejercicios específicos.',
            clase: 'alert-success'
        }
    };

    // Cambio de tipo de rutina
    tipoRutina.addEventListener('change', function() {
        const tipo = this.value;

        if (tipo === 'personalizada') {
            rutinaPersonalizadaCard.classList.remove('d-none');
        } else {
            rutinaPersonalizadaCard.classList.add('d-none');
        }

        // Mostrar información del tipo de rutina
        if (tipo && infoRutinas[tipo]) {
            const info = infoRutinas[tipo];
            tipoInfo.className = `alert ${info.clase}`;
            tipoInfo.innerHTML = `
                <h6 class="alert-heading mb-2">${info.titulo}</h6>
                <p class="mb-0">${info.descripcion}</p>
            `;
            tipoInfo.classList.remove('d-none');
        } else {
            tipoInfo.classList.add('d-none');
        }
    });

    // Manejar selección de grupos musculares
    document.querySelectorAll('.grupo-muscular-check').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const dia = this.dataset.dia;
            const grupoSeleccionado = this.value;
            const container = document.querySelector(`.ejercicios-dia-container[data-dia="${dia}"]`);
            
            // Limitar a 3 grupos musculares
            const gruposSeleccionados = document.querySelectorAll(
                `.grupo-muscular-check[data-dia="${dia}"]:checked`
            );
            
            if (gruposSeleccionados.length > 3) {
                this.checked = false;
                alert('Máximo 3 grupos musculares por día');
                return;
            }

            // Regenerar lista de ejercicios
            actualizarEjerciciosDia(dia);
        });
    });

    function actualizarEjerciciosDia(dia) {
        const container = document.querySelector(`.ejercicios-dia-container[data-dia="${dia}"]`);
        const gruposSeleccionados = Array.from(
            document.querySelectorAll(`.grupo-muscular-check[data-dia="${dia}"]:checked`)
        ).map(cb => cb.value);

        if (gruposSeleccionados.length === 0) {
            container.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Selecciona grupos musculares arriba para ver los ejercicios disponibles
                </div>
            `;
            actualizarContador(dia);
            return;
        }

        let html = '';
        
        gruposSeleccionados.forEach(grupo => {
            const ejercicios = ejerciciosPorGrupo[grupo] || [];
            
            if (ejercicios.length > 0) {
                html += `
                    <div class="grupo-section mb-3">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-dumbbell"></i> ${grupo}
                        </h6>
                        <div class="row">
                `;

                ejercicios.forEach(ejercicio => {
                    html += `
                        <div class="col-md-6 mb-3">
                            <div class="card ejercicio-card h-100">
                                <div class="card-body p-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input ejercicio-check" 
                                               type="checkbox" 
                                               value="${ejercicio.id}"
                                               data-dia="${dia}"
                                               data-nombre="${ejercicio.nombre}"
                                               id="ej_${dia}_${ejercicio.id}">
                                        <label class="form-check-label fw-bold" for="ej_${dia}_${ejercicio.id}">
                                            ${ejercicio.nombre}
                                        </label>
                                    </div>
                                    ${ejercicio.descripcion ? `<small class="text-muted">${ejercicio.descripcion}</small>` : ''}
                                    
                                    <div class="ejercicio-config mt-2 d-none" id="config_${dia}_${ejercicio.id}">
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <label class="form-label small">Series</label>
                                                <input type="number" 
                                                       class="form-control form-control-sm" 
                                                       name="ejercicios[${dia}_${ejercicio.id}][series]"
                                                       min="1" max="10" value="3">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label small">Reps</label>
                                                <input type="number" 
                                                       class="form-control form-control-sm" 
                                                       name="ejercicios[${dia}_${ejercicio.id}][repeticiones]"
                                                       min="1" max="50" value="10">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label small">Peso (kg)</label>
                                                <input type="number" 
                                                       class="form-control form-control-sm" 
                                                       name="ejercicios[${dia}_${ejercicio.id}][peso]"
                                                       min="0" step="0.5" placeholder="Opc">
                                            </div>
                                        </div>
                                        <input type="hidden" name="ejercicios[${dia}_${ejercicio.id}][ejercicio_id]" value="${ejercicio.id}">
                                        <input type="hidden" name="ejercicios[${dia}_${ejercicio.id}][dia_semana]" value="${dia}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            }
        });

        container.innerHTML = html;

        // Agregar eventos a los nuevos checkboxes de ejercicios
        container.querySelectorAll('.ejercicio-check').forEach(check => {
            check.addEventListener('change', function() {
                const ejercicioId = this.value;
                const dia = this.dataset.dia;
                const config = document.getElementById(`config_${dia}_${ejercicioId}`);
                
                if (this.checked) {
                    config.classList.remove('d-none');
                    // Hacer campos requeridos
                    config.querySelectorAll('input[type="number"]').forEach(input => {
                        if (!input.name.includes('peso')) {
                            input.required = true;
                        }
                    });
                } else {
                    config.classList.add('d-none');
                    // Quitar requerido
                    config.querySelectorAll('input').forEach(input => {
                        input.required = false;
                    });
                }
                
                actualizarContador(dia);
            });
        });

        actualizarContador(dia);
    }

    function actualizarContador(dia) {
        const count = document.querySelectorAll(`.ejercicio-check[data-dia="${dia}"]:checked`).length;
        const badge = document.querySelector(`.ejercicios-count-${dia}`);
        if (badge) {
            badge.textContent = `${count} ejercicio${count !== 1 ? 's' : ''}`;
            badge.className = `badge ms-2 ${count > 0 ? 'bg-success' : 'bg-secondary'}`;
        }
    }

    // Validación del formulario
    document.getElementById('rutinaForm').addEventListener('submit', function(e) {
        const tipo = tipoRutina.value;
        
        if (tipo === 'personalizada') {
            const ejerciciosSeleccionados = document.querySelectorAll('.ejercicio-check:checked');
            
            if (ejerciciosSeleccionados.length === 0) {
                e.preventDefault();
                alert('Debes seleccionar al menos un ejercicio para tu rutina personalizada');
                return false;
            }

            // Validar que los ejercicios seleccionados tengan sus campos llenos
            let valido = true;
            ejerciciosSeleccionados.forEach(check => {
                const dia = check.dataset.dia;
                const ejercicioId = check.value;
                const config = document.getElementById(`config_${dia}_${ejercicioId}`);
                
                const series = config.querySelector('input[name*="[series]"]');
                const reps = config.querySelector('input[name*="[repeticiones]"]');
                
                if (!series.value || !reps.value) {
                    valido = false;
                }
            });

            if (!valido) {
                e.preventDefault();
                alert('Por favor completa series y repeticiones para todos los ejercicios seleccionados');
                return false;
            }
        }
    });
});
</script>
@endsection