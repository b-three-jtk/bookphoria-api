<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserBookRequest extends FormRequest
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
            'book_id' => 'required|string|exists:books,id',
            'page_count' => 'required|integer|min:0',
            'status' => 'required|string|in:reading,owned,borrowed',
            'start_date' => 'nullable|date',
            'finish_date' => 'nullable|date|after:start_date',
        ];
    }
}
