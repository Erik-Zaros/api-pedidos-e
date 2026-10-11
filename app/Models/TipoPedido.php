<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoPedido extends Model
{
    protected $table = 'tbl_tipo_pedido';

    protected $fillable = ['nome', 'descricao', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'tipo_pedido');
    }
}
