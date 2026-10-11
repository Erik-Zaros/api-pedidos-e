<?php

namespace App\Http\Requests;

use App\Models\Pedido;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_pedido' => ['sometimes', 'required', 'integer', Rule::exists('tbl_tipo_pedido', 'id')->where('ativo', true)],
            'forma_pagamento' => ['sometimes', 'required', 'integer', Rule::exists('tbl_forma_pagamento', 'id')->where('ativo', true)],
            'observacao' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', Rule::in(Pedido::STATUS)],
            // se enviado, substitui todos os itens (somente pedidos pendentes)
            'itens' => ['sometimes', 'array', 'min:1'],
            'itens.*.item_id' => ['required_with:itens', 'integer', Rule::exists('tbl_item', 'id')->where('ativo', true)],
            'itens.*.quantidade' => ['required_with:itens', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
