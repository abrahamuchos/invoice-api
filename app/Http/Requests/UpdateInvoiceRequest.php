<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
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
        if($this->method() === 'PUT'){
            return [
                'customerId' => 'required|numeric',
                'amount' => 'required|decimal:0,2',
                'status' => ['required', Rule::in('V', 'B', 'P', 'v', 'b', 'p')],
                'billedDated' => 'required|date',
                'paidDated' => 'nullable|date',
            ];

        }else{
            return [
                'customerId' => 'sometimes|numeric',
                'amount' => 'sometimes|decimal:0,2',
                'status' => ['sometimes', Rule::in('V', 'B', 'P', 'v', 'b', 'p')],
                'billedDated' => 'sometimes|date',
                'paidDated' => 'sometimes|date',
            ];
        }
    }

    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        if($this->customerId){
           $this->merge([
               'customer_id' => $this->customerId,
           ]);
        }
        if($this->billedDated){
           $this->merge([
               'billed_dated' => $this->billedDated,
           ]);
        }
        if($this->paidDated){
           $this->merge([
               'paid_dated' => $this->paidDated,
           ]);
        }
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'status.in' => 'Status can be V, B, or P'
        ];
    }
}
