<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_CONFIRMADO = 'confirmado';
    public const STATUS_EM_PREPARO = 'em_preparo';
    public const STATUS_ENTREGUE = 'entregue';
    public const STATUS_CANCELADO = 'cancelado';

    public const STATUS = [
        self::STATUS_PENDENTE,
        self::STATUS_CONFIRMADO,
        self::STATUS_EM_PREPARO,
        self::STATUS_ENTREGUE,
        self::STATUS_CANCELADO,
    ];

    protected $table = 'tbl_pedido';

    protected $fillable = ['user_id', 'tipo_pedido', 'forma_pagamento', 'status', 'total', 'observacao'];

    protected $casts = ['total' => 'decimal:2'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tipoPedido(): BelongsTo
    {
        return $this->belongsTo(TipoPedido::class, 'tipo_pedido');
    }

    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }
}
