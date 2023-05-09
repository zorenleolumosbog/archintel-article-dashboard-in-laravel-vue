<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if (request()->method() === 'POST') {
            return [
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => 'email|required_with:password|unique:users|max:255',
                'password' => 'required_with:email|confirmed|min:8|max:255',
                'type' => 'required|in:Writer,Editor',
                'status' => 'required|in:Active,Inactive',
            ];
        }

        if (request()->method() === 'PUT' || request()->method() === 'PATCH') {
            return [
                'firstname' => 'sometimes|required|max:255',
                'lastname' => 'sometimes|required|max:255',
                'email' => 'sometimes|email|required_with:password|max:255,|unique:users,email,' . $this->route('user')->id,
                'password' => 'sometimes|confirmed|min:8|max:255',
                'type' => 'sometimes|required|in:Writer,Editor',
                'status' => 'sometimes|required|in:Active,Inactive',
    
                'current_password' => [
                    'sometimes',
                    'required_with:new_password',
                    function ($attribute, $value, $fail) {
                        if(!Hash::check(request()->current_password, Auth::user()->password)) {
                            $fail(__('The :attribute is incorrect.'));
                        }
                    },
                ],
                'new_password' => [
                    'sometimes',
                    'required_with:current_password',
                    'string',
                    'min:8',
                    'max:255',
                    'confirmed',
                    Rule::notIn([request()->current_password]), // ensure the new password is not the same as the current password
                ],
            ];
        }
    }
}
