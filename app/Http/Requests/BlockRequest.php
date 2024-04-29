<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockRequest extends FormRequest {


    public function rules(): array {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'required|boolean',
            'price' => 'required|numeric|min:0',
            'is_plain_text' => 'required|boolean',
            'passing_score' => 'nullable|integer|min:0',
        ];
    }
}
