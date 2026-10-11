<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'total' => $this->total,
            'observacao' => $this->observacao,
            'tipo_pedido' => new TipoPedidoResource($this->whenLoaded('tipoPedido')),
            'forma_pagamento' => new FormaPagamentoResource($this->whenLoaded('formaPagamento')),
            'itens' => PedidoItemResource::collection($this->whenLoaded('itens')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
