<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreRequest extends FormRequest
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
        return [
            '*.customerId' => 'required|numeric',
            '*.amount' => 'required|decimal:0,2',
            '*.status' => ['required', Rule::in('V', 'B', 'P', 'v', 'b', 'p')],
            '*.billedDated' => 'required|date',
            '*.paidDated' => 'nullable|date',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            '*.status.in' => 'Status can be V, B, or P'
        ];
    }

    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        $data = [];
        foreach ($this->toArray() as $item){
            $item['customer_id'] = $item['customerId'] ?? null;
            $item['billed_dated'] = $item['billedDated'] ?? null;
            $item['paid_dated'] = $item['paidDated'] ?? null;
            $data[] = $item;
        }
        $this->merge($data);
    }
}
