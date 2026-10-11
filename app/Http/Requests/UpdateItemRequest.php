<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['sometimes', 'required', 'string', 'max:120'],
            'descricao' => ['nullable', 'string'],
            'ativo' => ['sometimes', 'boolean'],
        ];
    }
}
