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
    $admin = $this->route('id');
    $adminId = is_object($admin) ? $admin->id : $admin;

    return [
        'usuario_id' => 'required|unique:administradors,usuario_id,' . $adminId . ',id',
        'area_responsabilidad' => 'required|string|max:255',
        'estado' => 'required|in:activo,inactivo',
    ];
    }
}
