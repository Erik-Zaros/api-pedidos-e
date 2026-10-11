<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrecoItem extends Model
{
    protected $table = 'tbl_preco_item';

    protected $fillable = ['item_id', 'preco', 'vigencia_inicio', 'vigencia_fim', 'ativo'];

    protected $casts = [
        'preco' => 'decimal:2',
        'vigencia_inicio' => 'date',
        'vigencia_fim' => 'date',
        'ativo' => 'boolean',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
