<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecepcionistaRequest extends FormRequest
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
    public function rules()
    {
        return [
            // Reglas para Usuario
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:8',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            
            // Reglas para Recepcionista
            'turno' => ['required', Rule::in(['mañana', 'tarde', 'noche'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo'])],
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nombre.required' => 'El campo **Nombre** es obligatorio.',
            'apellido.required' => 'El campo **Apellido** es obligatorio.',
            
            'email.required' => 'El **Email** es obligatorio.',
            'email.email' => 'Debe ingresar un formato de email válido.',
            'email.unique' => 'Este **Email** ya está registrado en el sistema.',
            
            'password.required' => 'La **Contraseña** es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.max' => 'La foto no puede pesar más de 2MB.',
            
            'turno.required' => 'Debe seleccionar un **Turno** para el recepcionista.',
            'turno.in' => 'El valor del turno seleccionado no es válido.',

            'estado.required' => 'Debe seleccionar un **Estado** para el recepcionista.',
        ];
    }
}
