<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookId = $this->route('id'); // Ambil ID dari parameter route

        return [
            'title' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'published_date' => 'required|string',
            'synopsis' => 'required|string',
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'pages' => 'required|integer|min:1',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'authors' => 'required|array|min:1',
            'authors.*' => 'required|string|max:255',

            'genres' => 'required|array|min:1',
            'genres.*' => 'required|string|max:255',
        ];
    }
}
