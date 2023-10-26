<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAttendanceSaveRequest extends FormRequest
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
            'longitude' => 'nullable|string|min:3|max:255',
            'latitude'  => 'nullable|string|min:3|max:255',
        ];
    }
}
