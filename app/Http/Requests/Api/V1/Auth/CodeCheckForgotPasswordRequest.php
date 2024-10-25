<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CodeCheckForgotPasswordRequest extends FormRequest
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

            'code' => 'required',
            'token' => 'required',
        ];
    }

    public function messages(): array
    {
        return [

            'code.required' => "Код відновлення обов`язкове для заповнення",
            'token.required' => "Token обов`язковий",
        ];
    }


}
