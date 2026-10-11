<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrecoItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'integer', 'exists:tbl_item,id'],
            'preco' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'vigencia_inicio' => ['required', 'date'],
            'vigencia_fim' => ['nullable', 'date', 'after_or_equal:vigencia_inicio'],
            'ativo' => ['sometimes', 'boolean'],
        ];
    }
}
