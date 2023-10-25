<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserSaveRequest extends FormRequest
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
        $prefix = $this->isMethod('put') ? 'sometimes' : 'required';

        return [
            'identifier' => $prefix . '|string|unique:users,identifier|min:3|max:255',
            'email'     => $prefix . '|string|unique:users,email|email|min:3|max:255',
            'password'  => $prefix . '|string|min:8|max:255',
            'role'      => $this->getRoleRules($prefix),
        ];
    }

    private function getRoleRules($prefix): array
    {
        return [
            $prefix,
            'string',
            Rule::in(array_values(User::ROLES))
        ];
    }
}
