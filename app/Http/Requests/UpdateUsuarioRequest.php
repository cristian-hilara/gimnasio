<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $usuario = $this->route('usuario'); //se crea una variable usuario que va recuperar en la ruta usuario
        return [
            'nombre' => 'required|max:255',
            'apellido' => 'required|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $usuario->id,
            //'password' => 'same:password_confirm',
            'telefono' => 'required|digits_between:7,15', // Puedes ajustar el rango según el formato esperado
            'estado' => 'required|in:activo,inactivo',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'rol' => 'required|exists:roles,name'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'   => 'El nombre es obligatorio.',
            'nombre.max'        => 'El nombre no puede superar los 255 caracteres.',

            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.max'      => 'El apellido no puede superar los 255 caracteres.',

            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Debe ingresar un correo electrónico válido.',
            'email.max'         => 'El correo electrónico no puede superar los 255 caracteres.',
            'email.unique'      => 'El correo electrónico ya está registrado por otro usuario.',

            'password.min'      => 'La contraseña debe tener al menos 5 caracteres.',
            'password.same'     => 'La confirmación de contraseña no coincide.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits_between' => 'El teléfono debe tener entre 7 y 15 dígitos.',

            'estado.required'   => 'Debe indicar el estado.',
            'estado.in'         => 'El estado debe ser "activo" o "inactivo".',

            'foto.image'        => 'El archivo debe ser una imagen.',
            'foto.mimes'        => 'La imagen debe estar en formato jpg, jpeg o png.',
            'foto.max'          => 'La imagen no puede superar los 2 MB.',

            'rol.required'      => 'Debe asignar un rol al usuario.',
            'rol.exists'        => 'El rol seleccionado no existe.',
        ];
    }
}
