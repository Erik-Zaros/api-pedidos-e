<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoPedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:80', 'unique:tbl_tipo_pedido,nome'],
            'descricao' => ['nullable', 'string', 'max:255'],
            'ativo' => ['sometimes', 'boolean'],
        ];
    }
}
