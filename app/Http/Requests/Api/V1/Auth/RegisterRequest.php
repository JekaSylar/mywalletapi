<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|email:rfc,dns',
            'password' => 'required|string|confirmed|min:6',
            'password_confirmation' => 'required|string|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Ім'я обов'язкове",
            'name.max' => "Ім'я перевищує 255 символів",
            'email.required' => "Email обов`язкове для заповнення",
            'email.unique' => "Email адреса вже використовується",
            'email.email' => "Email не валідний",
            'password.confirmed' => 'Паролі не співпадають',
            'password.required' => 'Пароль обов`язкове для заповнення',
            'password.min' => 'Мінімальна довжина пароля 6 символів',
            'password_confirmation' => 'Паролі не співпадають',
        ];
    }



}
