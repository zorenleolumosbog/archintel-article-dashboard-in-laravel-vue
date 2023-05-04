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
        return [
            'writer_user_id' => [
                'sometimes',
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'Writer');
                }),
            ],
            'editor_user_id' => [
                'sometimes',
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'Editor');
                }),
            ],
            'company_id' => 'sometimes|required|exists:companies,id',
            'image' => 'sometimes|required|image|mimes:jpg,jpeg,png|max:15360', // limit 15mb
            'title' => 'sometimes|required|unique:articles,title|max:255',
            'link' => 'sometimes|required|unique:articles,link|max:255',
            'date' => 'sometimes|required|date',
            'content' => 'sometimes|required',
            'status' => 'sometimes|required|in:For Edit,Published',
        ];
    }
}
