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
        return [
            'turno' => ['required', Rule::in(['mañana', 'tarde', 'noche'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo'])],
        ];
    }

    public function messages()
    {
        return [
            'turno.required' => 'Debe seleccionar un **Turno** para el recepcionista.',
            'turno.in' => 'El valor del turno seleccionado no es válido.',
            'estado.required' => 'Debe seleccionar un **Estado** para el recepcionista.',
        ];
    }
}
