@extends('layouts.template')
@section('title','Crear administrador')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/usuarios-form.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="card p-4">
    <div class="card-body">
        <h2 class="mb-4 text-center fw-bold"><i class="fa-solid fa-user-plus text-primary"></i> Crear Administrador</h2>
        <form action="{{ route('administrador.store')}}" method="post">
            @csrf
            <div class="row g-3">

                <!-- area-responsabilidad -->
                <div class="col-md-6">
                    <label for="area_responsabilidad" class="form-label">Area</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="area_responsabilidad" id="area_responsabilidad" class="form-control" value="{{old('area_responsabilidad')}}" >
                    </div>
                    @error('area_responsabilidad')
                    <span class="text-danger">{{$message}}</span>
                    @enderror

                </div>

                <!-- usauario_id -->


                <div class="col-md-6">
                    <label for="usuario_search" class="form-label">Usuario</label>
                    <input type="text" id="usuario_search" class="form-control" placeholder="Escribe para buscar usuario" autocomplete="off">
                    <input type="hidden" name="usuario_id" id="usuario_id" value="{{ old('usuario_id') }}">

                    @error('usuario_id')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- estado -->
                <div class="col-md-6">
                    <label for="estado" class="form-label">Estado</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-toggle-on"></i></span>
                        <select name="estado" id="estado" class="form-select">
                            <option value="" disabled selected>Seleccione estado</option>
                            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    @error('estado')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror

                </div>



                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                        <i class="fa-solid fa-save"></i> Guardar
                    </button>
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