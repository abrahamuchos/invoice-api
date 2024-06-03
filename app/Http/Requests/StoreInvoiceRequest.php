<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
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
            'customerId' => 'required|numeric',
            'amount' => 'required|decimal:0,2',
            'status' => ['required', Rule::in('V', 'B', 'P', 'v', 'b', 'p')],
            'billedDated' => 'required|date',
            'paidDated' => 'nullable|date',
        ];
    }

    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'customer_id' => $this->customerId,
            'billed_dated' => $this->billedDated,
            'paid_dated' => $this->paidDated,
        ]);
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
