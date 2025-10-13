<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdministradorRequest extends FormRequest
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
            'area_responsabilidad' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo',
        ];
    }
    public function messages(): array
    {
        return [
            'area_responsabilidad.required' => 'El área de responsabilidad es obligatoria.',
            'estado.required' => 'Debes seleccionar un estado.',
            'estado.in' => 'El estado debe ser activo o inactivo.',
        ];
    }
}
