@extends('layouts.template')
@section('title','Crear Membresía')

@push('css')
<style>
    .form-label {
        font-weight: 600;
    }
    .card-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    .duration-selector {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .duration-selector:hover {
        transform: scale(1.05);
    }
    .duration-selector.selected {
        border: 3px solid #28a745 !important;
        background-color: #d4edda;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-id-card"></i> Nueva Membresía
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('membresias.index')}}">Membresías</a></li>
        <li class="breadcrumb-item active">Crear Membresía</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus-circle"></i> Registrar Nueva Membresía
        </div>
        <div class="card-body">
            <form action="{{route('membresias.store')}}" method="POST">
                @csrf

                <div class="row">
                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">
                            <i class="fas fa-tag"></i> Nombre de la Membresía *
                        </label>
                        <input type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{old('nombre')}}"
                            placeholder="Ej: Membresía Mensual, Anual Premium"
                            required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Precio -->
                    <div class="col-md-6 mb-3">
                        <label for="precio" class="form-label">
                            <i class="fas fa-dollar-sign"></i> Precio (Bs.) *
                        </label>
                        <input type="number" 
                            name="precio" 
                            id="precio" 
                            step="0.01"
                            min="0"
                            class="form-control @error('precio') is-invalid @enderror"
                            value="{{old('precio')}}"
                            placeholder="Ej: 150.00"
                            required>
                        @error('precio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Selector de Duración (Visual) -->
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-calendar-alt"></i> Duración (selecciona una opción)
                        </label>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <div class="card duration-selector text-center p-3" onclick="selectDuration(30)">
                                    <i class="fas fa-calendar-day fa-2x text-success mb-2"></i>
                                    <h6>1 Mes</h6>
                                    <small class="text-muted">30 días</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="card duration-selector text-center p-3" onclick="selectDuration(90)">
                                    <i class="fas fa-calendar-week fa-2x text-success mb-2"></i>
                                    <h6>3 Meses</h6>
                                    <small class="text-muted">90 días</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="card duration-selector text-center p-3" onclick="selectDuration(180)">
                                    <i class="fas fa-calendar fa-2x text-success mb-2"></i>
                                    <h6>6 Meses</h6>
                                    <small class="text-muted">180 días</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="card duration-selector text-center p-3" onclick="selectDuration(365)">
                                    <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                                    <h6>1 Año</h6>
                                    <small class="text-muted">365 días</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Duración en días (campo oculto que se actualiza) -->
                    <div class="col-md-6 mb-3">
                        <label for="duracion_dias" class="form-label">
                            <i class="fas fa-hashtag"></i> Duración en Días *
                        </label>
                        <input type="number" 
                            name="duracion_dias" 
                            id="duracion_dias" 
                            min="1"
                            class="form-control @error('duracion_dias') is-invalid @enderror"
                            value="{{old('duracion_dias')}}"
                            placeholder="O ingresa días personalizados"
                            required>
                        @error('duracion_dias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Selecciona arriba o ingresa un valor personalizado
                        </small>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">
                            <i class="fas fa-toggle-on"></i> Estado *
                        </label>
                        <select name="estado" 
                            id="estado" 
                            class="form-select @error('estado') is-invalid @enderror" 
                            required>
                            <option value="1" {{ old('estado') == '1' ? 'selected' : '' }}>Activa</option>
                            <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactiva</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Descripción -->
                    <div class="col-md-12 mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left"></i> Descripción
                        </label>
                        <textarea 
                            name="descripcion" 
                            id="descripcion" 
                            rows="4"
                            class="form-control @error('descripcion') is-invalid @enderror"
                            placeholder="Describe los beneficios de esta membresía...">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{route('membresias.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Membresía
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function selectDuration(days) {
    // Remover selección previa
    document.querySelectorAll('.duration-selector').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Agregar selección al clickeado
    event.currentTarget.classList.add('selected');
    
    // Actualizar el input
    document.getElementById('duracion_dias').value = days;
}

// Si hay un valor antiguo, seleccionarlo
const oldValue = "{{ old('duracion_dias') }}";
if (oldValue) {
    const cards = document.querySelectorAll('.duration-selector');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            const dias = this.querySelector('small').textContent.match(/\d+/)[0];
            if (dias == oldValue) {
                this.classList.add('selected');
            }
        });
    });
}
</script>
@endpush