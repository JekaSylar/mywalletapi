<?php

namespace App\Http\Requests\Api\V1\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $account = $this->route('account');
        return auth()->check() && $account && $account->user->id === auth()->id();
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
            'balance' => 'numeric|min:0',
            'currency' => 'string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Назва рахунку обов`язкове для заповнення",
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
