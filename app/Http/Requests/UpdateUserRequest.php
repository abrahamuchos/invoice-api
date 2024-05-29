<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
                'name' => 'required|min:2|max:65',
                'email' => 'required|email|unique:users,email',
                'isAdmin' => 'required|boolean',
            ];

        }else{
            return [
                'name' => 'sometimes|min:2|max:65',
                'email' => 'sometimes|email|unique:users,email',
                'isAdmin' => 'sometimes|boolean',
            ];
        }
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if($this->isAdmin){
            $this->merge([
                'is_admin' => 'isAdmin'
            ]);
        }
    }
}
