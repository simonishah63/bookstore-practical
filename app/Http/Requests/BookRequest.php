<?php

namespace App\Http\Requests;

class BookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'genre' => 'required|max:255',
            'description' => 'nullable|max:5000',
            'isbn' => 'required|numeric',
            'publisher' => 'required|max:255',
            'published_at' => 'required|date',
            'uploadImage' => 'nullable|image|mimes:png,jpg,jpeg,gif',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [];
    }
}
