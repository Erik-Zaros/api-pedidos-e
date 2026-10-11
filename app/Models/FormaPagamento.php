<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormaPagamento extends Model
{
    protected $table = 'tbl_forma_pagamento';

    protected $fillable = ['nome', 'descricao', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'forma_pagamento');
    }
}
