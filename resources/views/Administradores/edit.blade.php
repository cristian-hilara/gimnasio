@extends('layouts.template')
@section('title','Editar administrador')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/usuarios-form.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="card p-4">
    <div class="card-body">
        <h2 class="mb-4 text-center fw-bold"><i class="fa-solid fa-user-plus text-primary"></i> Editar Administrador</h2>
        <form action="{{ route('administradors.update',$administrador->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row g-3">



                <h5 class="mb-3 text-primary"><i class="fas fa-briefcase"></i> Datos del Recepcionista</h5>


                <div class="col-md-12 mb-4 d-flex align-items-center gap-3">
                    <img src="{{ asset('storage/' . $administrador->usuario->foto) }}"
                        alt="Foto de {{ $administrador->usuario->nombre }}"
                        class="rounded-circle" width="50" height="50">
                    <div>
                        <label class="form-label">
                            <i class="fas fa-user"></i> Usuario asociado
                        </label>
                        <input type="text" class="form-control"
                            style="width: 200%; max-width: 600px;"
                            value="{{ $administrador->usuario->nombre }} {{ $administrador->usuario->apellido }} - {{ $administrador->usuario->email }}"
                            readonly>
                    </div>
                </div>
                <!-- area-responsabilidad -->
                <div class="col-md-6">
                    <label for="area_responsabilidad" class="form-label">Area</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="area_responsabilidad" id="area_responsabilidad" class="form-control" value="{{old('area_responsabilidad',$administrador->area_responsabilidad)}}" >
                    </div>
                    @error('area_responsabilidad')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>
                <!-- estado -->
                <div class="col-md-6">
                    <label for="estado" class="form-label">Estado</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-toggle-on"></i></span>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="" disabled>Seleccione estado</option>
                            <option value="activo" {{ old('estado', $administrador->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $administrador->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    @error('estado')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror


                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                            <i class="fa-solid fa-save"></i> Actualizar
                        </button>
                    </div>
                </div>
            </div>    
        </form>
    </div>
</div>

@push('js')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('js/autocomplete-usuarios.js') }}"></script>
@endpush
@endsection