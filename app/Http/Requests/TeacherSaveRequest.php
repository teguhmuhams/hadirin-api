<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      => 'required|string|min:3|max:255',
            'nip'       => 'required|string|min:3|max:255',
            'birthdate' => 'required|date',
            'gender'    => 'required|string|in:Laki-Laki,Perempuan',
            'user_id'   => 'required|integer|exists:users,id'
        ];
    }
}
