<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
        if ($this->method() == 'PUT') {
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
        } else {
            return [
                'name' => 'sometimes',
                'type' => ['sometimes', Rule::in(['I', 'B', 'i', 'b'])],
                'email' => 'sometimes|email|unique:customers,email',
                'address' => 'sometimes|max:255',
                'city' => 'sometimes|max:65',
                'state' => 'sometimes|max:65',
                'country' => 'sometimes|max:65',
                'postalCode' => 'sometimes',
            ];
        }
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if($this->postalCode){
            $this->merge([
                'postal_code' => $this->postalCode
            ]);
        }
    }
}
