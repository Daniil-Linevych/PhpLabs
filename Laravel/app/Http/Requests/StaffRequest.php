<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'exhibitions' => 'array',
            'exhibitions.*' => 'exists:exhibitions,id',
        ];
    }
}
