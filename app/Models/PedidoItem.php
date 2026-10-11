<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoItem extends Model
{
    protected $table = 'tbl_pedido_item';

    protected $fillable = ['pedido_id', 'item_id', 'quantidade', 'preco_unitario', 'subtotal'];

    protected $casts = [
        'quantidade' => 'integer',
        'preco_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
