<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'preco_atual' => $this->whenLoaded('precos', fn () => $this->precoVigente()?->preco),
            'precos' => PrecoItemResource::collection($this->whenLoaded('precos')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
