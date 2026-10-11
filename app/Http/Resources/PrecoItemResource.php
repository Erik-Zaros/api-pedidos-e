<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrecoItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'item' => $this->whenLoaded('item', fn () => ['id' => $this->item->id, 'nome' => $this->item->nome]),
            'preco' => $this->preco,
            'vigencia_inicio' => $this->vigencia_inicio?->toDateString(),
            'vigencia_fim' => $this->vigencia_fim?->toDateString(),
            'ativo' => $this->ativo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
