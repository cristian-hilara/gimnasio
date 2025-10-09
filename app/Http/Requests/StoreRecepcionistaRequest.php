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
            'usuario_id' => 'required|exists:usuarios,id|unique:recepcionistas,usuario_id',
            'turno' => ['required', Rule::in(['mañana', 'tarde', 'noche'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo'])],
        ];
    }

    public function messages()
    {
        return [
            'usuario_id.required' => 'Debe seleccionar un usuario',
            'usuario_id.unique' => 'Este usuario ya está registrado como recepcionista',
            'turno.required' => 'Debe seleccionar un **Turno** para el recepcionista.',
            'turno.in' => 'El valor del turno seleccionado no es válido.',
            'estado.required' => 'Debe seleccionar un **Estado** para el recepcionista.',
        ];
    }
}
