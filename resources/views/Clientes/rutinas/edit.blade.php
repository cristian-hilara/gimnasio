@extends('layouts.templateCliente')

@section('title','Editar Rutina')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Editar Rutina: {{ $rutina->nombre }}
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

                    <form action="{{ route('cliente.rutinas.update',$rutina->id) }}" method="POST" id="editRutinaForm">
                        @csrf @method('PUT')

                        <!-- Información Básica -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información Básica</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Nombre de la Rutina <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre" class="form-control" 
                                               value="{{ old('nombre', $rutina->nombre) }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Estado</label>
                                        <select name="estado" class="form-select">
                                            <option value="activa" {{ old('estado', $rutina->estado) == 'activa' ? 'selected' : '' }}>Activa</option>
                                            <option value="inactiva" {{ old('estado', $rutina->estado) == 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $rutina->descripcion) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Rutina Personalizada por Días -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-calendar-week"></i> Planificación Semanal</h5>
                                <small class="text-muted">Modifica los ejercicios de cada día de tu rutina</small>
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
                                    
                                    // Agrupar ejercicios existentes por día
                                    $ejerciciosPorDia = $rutina->ejercicios->groupBy('pivot.dia_semana');
                                @endphp

                                <div class="accordion" id="diasAccordion">
                                    @foreach($diasSemana as $diaKey => $diaNombre)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#dia_{{ $diaKey }}"
                                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                                    <i class="fas fa-calendar-day me-2"></i> {{ $diaNombre }}
                                                    <span class="badge bg-primary ms-2 ejercicios-count-{{ $diaKey }}">
                                                        {{ isset($ejerciciosPorDia[$diaKey]) ? $ejerciciosPorDia[$diaKey]->count() : 0 }} ejercicios
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="dia_{{ $diaKey }}" 
                                                 class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                                 data-bs-parent="#diasAccordion">
                                                <div class="accordion-body">
                                                    <!-- Selector de grupos musculares -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">
                                                            <i class="fas fa-layer-group"></i> 
                                                            Selecciona Grupos Musculares (máximo 3)
                                                        </label>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @php
                                                                // Obtener grupos musculares ya seleccionados en este día
                                                                $gruposSeleccionados = isset($ejerciciosPorDia[$diaKey]) 
                                                                    ? $ejerciciosPorDia[$diaKey]->pluck('grupo_muscular')->unique()->toArray()
                                                                    : [];
                                                            @endphp
                                                            
                                                            @foreach($gruposMusculares as $grupo)
                                                                <div class="form-check">
                                                                    <input class="form-check-input grupo-muscular-check" 
                                                                           type="checkbox" 
                                                                           value="{{ $grupo }}"
                                                                           data-dia="{{ $diaKey }}"
                                                                           id="grupo_{{ $diaKey }}_{{ $loop->index }}"
                                                                           {{ in_array($grupo, $gruposSeleccionados) ? 'checked' : '' }}>
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
                                                        @if(isset($ejerciciosPorDia[$diaKey]) && $ejerciciosPorDia[$diaKey]->count() > 0)
                                                            @php
                                                                $ejerciciosDelDia = $ejerciciosPorDia[$diaKey]->groupBy('grupo_muscular');
                                                            @endphp
                                                            
                                                            @foreach($ejerciciosDelDia as $grupo => $ejerciciosGrupo)
                                                                <div class="grupo-section mb-3">
                                                                    <h6 class="text-primary mb-3">
                                                                        <i class="fas fa-dumbbell"></i> {{ $grupo }}
                                                                    </h6>
                                                                    <div class="row">
                                                                        @foreach($ejerciciosGrupo as $ejercicio)
                                                                            <div class="col-md-6 mb-3">
                                                                                <div class="card ejercicio-card h-100">
                                                                                    <div class="card-body p-3">
                                                                                        <div class="form-check mb-2">
                                                                                            <input class="form-check-input ejercicio-check" 
                                                                                                   type="checkbox" 
                                                                                                   value="{{ $ejercicio->id }}"
                                                                                                   data-dia="{{ $diaKey }}"
                                                                                                   data-nombre="{{ $ejercicio->nombre }}"
                                                                                                   id="ej_{{ $diaKey }}_{{ $ejercicio->id }}"
                                                                                                   checked>
                                                                                            <label class="form-check-label fw-bold" for="ej_{{ $diaKey }}_{{ $ejercicio->id }}">
                                                                                                {{ $ejercicio->nombre }}
                                                                                            </label>
                                                                                        </div>
                                                                                        @if($ejercicio->descripcion)
                                                                                            <small class="text-muted">{{ $ejercicio->descripcion }}</small>
                                                                                        @endif
                                                                                        
                                                                                        <div class="ejercicio-config mt-2" id="config_{{ $diaKey }}_{{ $ejercicio->id }}">
                                                                                            <div class="row g-2">
                                                                                                <div class="col-4">
                                                                                                    <label class="form-label small">Series</label>
                                                                                                    <input type="number" 
                                                                                                           class="form-control form-control-sm" 
                                                                                                           name="ejercicios[{{ $diaKey }}_{{ $ejercicio->id }}][series]"
                                                                                                           min="1" max="10" 
                                                                                                           value="{{ $ejercicio->pivot->series }}"
                                                                                                           required>
                                                                                                </div>
                                                                                                <div class="col-4">
                                                                                                    <label class="form-label small">Reps</label>
                                                                                                    <input type="number" 
                                                                                                           class="form-control form-control-sm" 
                                                                                                           name="ejercicios[{{ $diaKey }}_{{ $ejercicio->id }}][repeticiones]"
                                                                                                           min="1" max="50" 
                                                                                                           value="{{ $ejercicio->pivot->repeticiones }}"
                                                                                                           required>
                                                                                                </div>
                                                                                                <div class="col-4">
                                                                                                    <label class="form-label small">Peso (kg)</label>
                                                                                                    <input type="number" 
                                                                                                           class="form-control form-control-sm" 
                                                                                                           name="ejercicios[{{ $diaKey }}_{{ $ejercicio->id }}][peso]"
                                                                                                           min="0" step="0.5" 
                                                                                                           value="{{ $ejercicio->pivot->peso }}"
                                                                                                           placeholder="Opc">
                                                                                                </div>
                                                                                            </div>
                                                                                            <input type="hidden" name="ejercicios[{{ $diaKey }}_{{ $ejercicio->id }}][ejercicio_id]" value="{{ $ejercicio->id }}">
                                                                                            <input type="hidden" name="ejercicios[{{ $diaKey }}_{{ $ejercicio->id }}][dia_semana]" value="{{ $diaKey }}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="alert alert-info">
                                                                <i class="fas fa-info-circle"></i> 
                                                                Selecciona grupos musculares arriba para ver los ejercicios disponibles
                                                            </div>
                                                        @endif
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
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('cliente.rutinas.show', $rutina->id) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver Rutina
                            </a>
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
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
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

    // Manejar checkboxes de ejercicios existentes
    document.querySelectorAll('.ejercicio-check').forEach(check => {
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
                // Quitar requerido y deshabilitar
                config.querySelectorAll('input').forEach(input => {
                    input.required = false;
                    input.disabled = true;
                });
            }
            
            actualizarContador(dia);
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
    document.getElementById('editRutinaForm').addEventListener('submit', function(e) {
        const ejerciciosSeleccionados = document.querySelectorAll('.ejercicio-check:checked');
        
        if (ejerciciosSeleccionados.length === 0) {
            e.preventDefault();
            alert('Debes seleccionar al menos un ejercicio para tu rutina');
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
    });

    // Inicializar contadores al cargar
    @foreach($diasSemana as $diaKey => $diaNombre)
        actualizarContador('{{ $diaKey }}');
    @endforeach
});
</script>
@endsection