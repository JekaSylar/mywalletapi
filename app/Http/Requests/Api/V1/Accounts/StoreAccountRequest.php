<?php

namespace App\Http\Requests\Api\V1\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'balance' => 'required|numeric|min:0',
            'currency' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Назва рахунку обов`язкове для заповнення",
            'balance.required' => "Баланс обов`язкове для заповнення",
            'balance.numeric' => "Має буде число",
            'balance.min' => "Минимальне значення 0",
            'currency.required' => 'Обов`язкове для заповнення',
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }
}
