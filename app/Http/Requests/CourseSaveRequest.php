<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseSaveRequest extends FormRequest
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
            'name'          => 'required|string|min:3|max:255',
            'year'          => 'required|string|min:3|max:255',
            'day'           => 'nullable|string|min:3|max:255',
            'status'        => 'required|string|min:3|max:255',
            'grade_id'      => 'required|integer|exists:grades,id',
            'teacher_id'    => 'nullable|integer|exists:teachers,id'
        ];
    }
}
