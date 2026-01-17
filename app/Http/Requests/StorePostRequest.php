<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
    public function rules()
    {
        return [
            'caption' => 'nullable|string|max:280',
            'media' => 'nullable|array|max:6',
            'media.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov|max:51200',
        ];
    }
}
