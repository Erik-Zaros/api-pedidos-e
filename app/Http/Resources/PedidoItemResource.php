<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'item' => $this->whenLoaded('item', fn () => ['id' => $this->item->id, 'nome' => $this->item->nome]),
            'quantidade' => $this->quantidade,
            'preco_unitario' => $this->preco_unitario,
            'subtotal' => $this->subtotal,
        ];
    }
}
