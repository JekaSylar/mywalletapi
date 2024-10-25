<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|email:rfc,dns',
            'password' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => "Email обов`язкове для заповнення",
            'email.email' => "Email не валідний",
            'password.required' => 'Пароль обов`язкове для заповнення',
        ];
    }
}