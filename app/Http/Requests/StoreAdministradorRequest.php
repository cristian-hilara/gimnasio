<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdministradorRequest extends FormRequest
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
        return [
            'usuario_id' => 'required|unique:administradors,usuario_id',
            'area_responsabilidad' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo',  
        ];
    }

    public function messages()
    {
        return [
            'usuario_id.unique' => 'El usuario seleccionado ya está registrado como administrador.',
            'usuario_id.required' => 'Debes seleccionar un usuario.',
            'area_responsabilidad.required' => 'El área de responsabilidad es obligatoria.',
            'estado.required' => 'El estado es obligatorio.',
            
        ];
    }
}
