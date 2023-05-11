<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
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
                'writer_user_id' => [
                    'nullable',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('type', 'Writer');
                    }),
                ],
                'editor_user_id' => [
                    'nullable',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('type', 'Editor');
                    }),
                ],
                'company_id' => 'required|exists:companies,id',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:3072', // limit 3mb
                'title' => 'required|unique:articles,title|max:255',
                'link' => 'required|unique:articles,link|max:255',
                'date' => 'required|date',
                'content' => 'required',
                'status' => 'required|in:For Edit,Published',
            ];
        }

        if (request()->method() === 'PUT' || request()->method() === 'PATCH') {
            return [
                'company_id' => 'sometimes|required|exists:companies,id',
                'image' => 'sometimes|required|image|mimes:jpg,jpeg,png|max:3072', // limit 3mb
                'title' => 'sometimes|required|max:255|unique:articles,title,' . $this->route('article')->id,
                'link' => 'sometimes|required|max:255|unique:articles,link,' . $this->route('article')->id,
                'date' => 'sometimes|required|date',
                'content' => 'sometimes|required',
                'status' => 'sometimes|required|in:For Edit,Published',
            ];
        }
    }
}
