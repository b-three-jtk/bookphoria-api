<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'published_date' => 'required|date',
            'synopsis' => 'required|string',
            'isbn' => 'nullable|string|max:20|unique:books',
            'pages' => 'required|integer|min:1',
            'cover' => 'required|string',
        ];
    }
}
