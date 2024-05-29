<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user !== null && $user->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
            'email' => 'required|email|unique:customers,email',
            'address' => 'required|max:255',
            'city' => 'required|max:65',
            'state' => 'required|max:65',
            'country' => 'required|max:65',
            'postalCode' => 'required',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'postal_code' => $this->postalCode
        ]);
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'type.in' => 'Type can be I, or B'
        ];
    }
}
