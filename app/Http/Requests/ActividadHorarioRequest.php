<?php

namespace App\Http\Requests;

use App\Models\ActividadHorario;
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $salaId       = $this->input('sala_id');
            $instructorId = $this->input('instructor_id');
            $diaSemana    = $this->input('dia_semana');
            $horaInicio   = $this->input('hora_inicio');
            $horaFin      = $this->input('hora_fin');

            // Si estamos editando, obtener el ID actual para excluirlo
            $horarioId = $this->route('actividad_horario')?->id;

            // 🔹 Validar conflicto en la sala
            $conflictoSala = ActividadHorario::where('sala_id', $salaId)
                ->where('dia_semana', $diaSemana)
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                        ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                        ->orWhere(function ($q) use ($horaInicio, $horaFin) {
                            $q->where('hora_inicio', '<=', $horaInicio)
                                ->where('hora_fin', '>=', $horaFin);
                        });
                })
                ->when($horarioId, fn($q) => $q->where('id', '!=', $horarioId)) // excluir el mismo registro
                ->exists();

            if ($conflictoSala) {
                $validator->errors()->add('sala_id', 'La sala ya está ocupada en ese horario.');
            }

            // 🔹 Validar conflicto en el instructor
            $conflictoInstructor = ActividadHorario::where('instructor_id', $instructorId)
                ->where('dia_semana', $diaSemana)
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                        ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                        ->orWhere(function ($q) use ($horaInicio, $horaFin) {
                            $q->where('hora_inicio', '<=', $horaInicio)
                                ->where('hora_fin', '>=', $horaFin);
                        });
                })
                ->when($horarioId, fn($q) => $q->where('id', '!=', $horarioId)) // excluir el mismo registro
                ->exists();

            if ($conflictoInstructor) {
                $validator->errors()->add('instructor_id', 'El instructor ya tiene otra actividad en ese horario.');
            }
        });
    }
}
