<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormaPagamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:80', 'unique:tbl_forma_pagamento,nome'],
            'descricao' => ['nullable', 'string', 'max:255'],
            'ativo' => ['sometimes', 'boolean'],
        ];
    }
}
