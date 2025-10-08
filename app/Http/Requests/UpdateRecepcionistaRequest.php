<?php

namespace App\Http\Requests;

use App\Models\Recepcionista;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecepcionistaRequest extends FormRequest
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
        // Obtenemos el ID del recepcionista desde el segmento de la URL
        $recepcionistaId = $this->route('recepcionista');
        
        // Buscamos el modelo para obtener el usuario_id
        $recepcionista = Recepcionista::findOrFail($recepcionistaId);
        $usuarioId = $recepcionista->usuario_id;
        
        return [
            // Reglas para Usuario
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                // Ignorar el email del usuario actual al validar unicidad
                Rule::unique('usuarios', 'email')->ignore($usuarioId),
            ],
            
            'password' => 'nullable|string|min:8', // La contraseña es opcional al actualizar
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
            'email.unique' => 'Este **Email** ya está asociado a otra cuenta.',
            
            'password.min' => 'Si ingresa una **Contraseña**, debe tener al menos :min caracteres.',
            
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.max' => 'La foto no puede pesar más de 2MB.',
            
            'turno.required' => 'Debe seleccionar un **Turno** para el recepcionista.',
            'turno.in' => 'El valor del turno seleccionado no es válido.',

            'estado.required' => 'Debe seleccionar un **Estado** para el recepcionista.',
        ];
    }
}
