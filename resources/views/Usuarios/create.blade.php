@extends('layouts.template')

@section('title','Crear usuario')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/usuarios-form.css') }}" rel="stylesheet">
@endpush

@section('content')



<div class="card p-4">
    <div class="card-body">
        <h2 class="mb-4 text-center fw-bold"><i class="fa-solid fa-user-plus text-primary"></i> Crear Usuario</h2>
        <form action="{{ route('usuarios.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                <!-- Nombre -->
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombres</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}" required>
                    </div>
                    @error('nombre')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Apellido -->
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellidos</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="apellido" id="apellido" class="form-control" value="{{old('apellido')}}" required>
                    </div>
                    @error('apellido')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Correo -->
                <div class="col-md-6">
                    <label for="email" class="form-label">Correo</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}" required>
                    </div>
                    @error('email')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                        <input type="number" name="telefono" id="telefono" class="form-control" value="{{old('telefono')}}" required>
                    </div>
                    @error('telefono')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="col-md-6">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-text">Escriba una contraseña segura, debe incluir números.</div>
                    @error('password')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div class="col-md-6">
                    <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
                    </div>
                    <div class="form-text">Vuelva a escribir su contraseña.</div>
                    @error('password_confirm')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Foto -->
                <div class="col-md-6">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                    @error('foto')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror

                </div>

                <!-- Rol -->
                <div class="col-md-6">
                    <label for="rol" class="form-label">Seleccionar Rol</label>
                    <select name="rol" id="rol" class="form-select" required>
                        <option value="" selected disabled>Seleccione:</option>
                        @foreach ($roles as $item)
                        <option value="{{$item->name}}" @selected(old('rol')==$item->name)>{{$item->name}}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Seleccione el rol del usuario.</div>
                    @error('rol')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>
                <!-- Estado -->
                <div class="col-md-6">
                    <label for="estado" class="form-label">Estado</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-toggle-on"></i></span>
                        <select name="estado" id="estado" class="form-select" required>
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



@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush