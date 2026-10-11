<?php

namespace App\Jobs;

use App\Models\Pedido;
use App\Repositories\PedidoRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessarPedidoJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(public int $pedidoId)
    {
    }

    public function handle(PedidoRepository $pedidos): void
    {
        $pedido = $pedidos->find($this->pedidoId);

        if (! $pedido || $pedido->status !== Pedido::STATUS_PENDENTE) {
            return;
        }

        $total = round((float) $pedido->itens()->sum('subtotal'), 2);

        $pedidos->update($pedido, [
            'total' => $total,
            'status' => Pedido::STATUS_CONFIRMADO,
        ]);

        Log::info("Pedido #{$pedido->id} confirmado. Total: R$ ".number_format($total, 2, ',', '.'));
    }
}
