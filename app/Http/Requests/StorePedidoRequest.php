<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_pedido' => ['required', 'integer', Rule::exists('tbl_tipo_pedido', 'id')->where('ativo', true)],
            'forma_pagamento' => ['required', 'integer', Rule::exists('tbl_forma_pagamento', 'id')->where('ativo', true)],
            'observacao' => ['nullable', 'string', 'max:255'],
            'itens' => ['required', 'array', 'min:1'],
            'itens.*.item_id' => ['required', 'integer', Rule::exists('tbl_item', 'id')->where('ativo', true)],
            'itens.*.quantidade' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
