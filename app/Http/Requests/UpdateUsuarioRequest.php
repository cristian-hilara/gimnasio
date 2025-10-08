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
        $usuario=$this->route('usuario');//se crea una variable usuario que va recuperar en la ruta usuario
        return [
            'nombre'=>'required|max:255',
            'apellido'=>'required|max:255',
            'email'=>'required|email|max:255|unique:usuarios,email,'.$usuario->id,
            'password'=>'same:password_confirm',
            'telefono'=> 'required|digits_between:7,15', // Puedes ajustar el rango según el formato esperado
            'estado'=>'required|in:activo,inactivo',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            'rol'=>'required|exists:roles,name'
        ];
    }
}
