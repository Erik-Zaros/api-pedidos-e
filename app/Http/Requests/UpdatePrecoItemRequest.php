<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrecoItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['sometimes', 'required', 'integer', 'exists:tbl_item,id'],
            'preco' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'vigencia_inicio' => ['sometimes', 'required', 'date'],
            'vigencia_fim' => ['nullable', 'date', 'after_or_equal:vigencia_inicio'],
            'ativo' => ['sometimes', 'boolean'],
        ];
    }
}
