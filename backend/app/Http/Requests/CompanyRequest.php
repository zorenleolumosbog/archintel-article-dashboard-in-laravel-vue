<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if (request()->method() === 'POST') {
            return [
                'logo' => 'required|image|mimes:jpg,jpeg,png|max:3072', // limit 3mb
                'name' => 'required|max:255',
                'status' => 'required|in:Active,Inactive',
            ];
        }

        if (request()->method() === 'PUT' || request()->method() === 'PATCH') {
            return [
                'logo' => 'sometimes|required|image|mimes:jpg,jpeg,png|max:3072', // limit 3mb
                'name' => 'sometimes|required|max:255',
                'status' => 'sometimes|required|in:Active,Inactive',
            ];
        }
    }
}
