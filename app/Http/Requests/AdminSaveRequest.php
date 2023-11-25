<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AdminSaveRequest extends FormRequest
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
            'name'              => 'required|string|min:3|max:255',
            'email'             => 'required|string|unique:users,email|email|min:3|max:255',
            'password'          => 'required|string|min:8|max:255',
            'role'              => 'required|in:' . User::ROLE_ADMIN,
            'employee_number'   => 'required|string|unique:admins,employee_number|min:3|max:255',
            'level'             => 'required|string|in:super-admin',
        ];
    }
}
