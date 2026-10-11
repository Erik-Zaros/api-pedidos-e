<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFormaPagamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['sometimes', 'required', 'string', 'max:80', Rule::unique('tbl_forma_pagamento', 'nome')->ignore($this->route('id'))],
            'descricao' => ['nullable', 'string', 'max:255'],
            'ativo' => ['sometimes', 'boolean'],
        ];
    }
}
