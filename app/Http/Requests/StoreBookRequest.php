<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'published_date' => 'required|string',
            'synopsis' => 'required|string',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'pages' => 'required|integer|min:1',
            'cover' => 'nullable|string',

            'authors' => 'required|array|min:1',
            'authors.*' => 'required|string|max:255',

            'genres' => 'required|array|min:1',
            'genres.*' => 'required|string|max:255',

            'user_status' => 'required|string|in:unread,reading,finished,owned',
            'user_page_count' => 'required|integer|min:0',
        ];
    }
}
