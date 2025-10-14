<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadHorarioRequest extends FormRequest
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
            'actividad_id' => 'required|exists:actividades,id',
            'dia_semana' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'cupo_maximo' => 'required|integer|min:1',
            'instructor_id' => 'required|exists:instructors,id',
            'sala_id' => 'required|exists:salas,id',
            'estado' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            
        'actividad_id.required' => 'Debe seleccionar una actividad.',
        'actividad_id.exists' => 'La actividad seleccionada no existe.',

        'dia_semana.required' => 'Debe seleccionar un día de la semana.',
        'dia_semana.string' => 'El día debe ser un texto válido.',

        'hora_inicio.required' => 'Debe ingresar la hora de inicio.',
        'hora_inicio.date_format' => 'La hora de inicio debe tener el formato HH:mm.',

        
        'hora_fin.required' => 'Debe ingresar la hora de fin.',
        'hora_fin.date_format' => 'La hora de fin debe tener el formato HH:mm.',
        'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        'cupo_maximo.required' => 'Debe ingresar el cupo máximo.',
        'cupo_maximo.integer' => 'El cupo debe ser un número entero.',
        'cupo_maximo.min' => 'El cupo debe ser al menos 1 persona.',
        'instructor_id.required' => 'Debe seleccionar un instructor.',
        'instructor_id.exists' => 'El instructor seleccionado no existe.',
        'sala_id.required' => 'Debe seleccionar una sala.',
        'sala_id.exists' => 'La sala seleccionada no existe.',
        'estado.required' => 'Debe indicar el estado del horario.',
        'estado.boolean' => 'El estado debe ser verdadero o falso.'
            
        ];
    }
}
