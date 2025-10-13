<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActividadRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'tipo_actividad' => 'required|in:maquina,baile',
            'dias' => 'required|array|min:1',
            'dias.*' => 'in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'cupo_maximo' => 'required|integer|min:1',
            'estado' => 'required|boolean',
            'instructor_id' => 'required|exists:instructors,id',
            'sala_id' => 'required|exists:salas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la actividad es obligatorio.',
            'tipo_actividad.in' => 'El tipo de actividad debe ser "maquina" o "baile".',
            'dias.required' => 'Debes seleccionar al menos un día.',
            'dias.*.in' => 'Los días seleccionados no son válidos.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'cupo_maximo.required' => 'El cupo máximo es obligatorio.',
            'cupo_maximo.integer' => 'El cupo debe ser un número entero.',
            'estado.required' => 'El estado de la actividad es obligatorio.',
            'instructor_id.exists' => 'El instructor seleccionado no existe.',
            'sala_id.exists' => 'La sala seleccionada no existe.',
        ];
    }
}
