<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    public function rules(): array {
        return [
            'block_id' => 'required|integer|exists:quiz_blocks,id',
            'ticket' => 'required|integer|min:1|max:1000',
            'order' => 'nullable|integer|min:1|max:1000',
            'text' => 'required|string',
            'description' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|min:1',
            'right' => ['required','integer','min:0', 'max:' . count($this->options) - 1],
        ];
    }
}
