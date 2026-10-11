<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $table = 'tbl_item';

    protected $fillable = ['nome', 'descricao', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function precos(): HasMany
    {
        return $this->hasMany(PrecoItem::class, 'item_id');
    }

    public function pedidoItens(): HasMany
    {
        return $this->hasMany(PedidoItem::class, 'item_id');
    }

    public function precoVigente(): ?PrecoItem
    {
        $hoje = now()->toDateString();

        return $this->precos
            ->filter(fn (PrecoItem $p) => $p->ativo
                && $p->vigencia_inicio->toDateString() <= $hoje
                && ($p->vigencia_fim === null || $p->vigencia_fim->toDateString() >= $hoje))
            ->sortByDesc(fn (PrecoItem $p) => $p->vigencia_inicio->toDateString().'-'.str_pad((string) $p->id, 10, '0', STR_PAD_LEFT))
            ->first();
    }
}
