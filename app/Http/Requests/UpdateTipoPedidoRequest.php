<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoPedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['sometimes', 'required', 'string', 'max:80', Rule::unique('tbl_tipo_pedido', 'nome')->ignore($this->route('id'))],
            'descricao' => ['nullable', 'string', 'max:255'],
            'ativo' => ['sometimes', 'boolean'],
        ];
    }
}
