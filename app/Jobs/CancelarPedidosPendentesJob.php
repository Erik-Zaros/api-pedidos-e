<?php

namespace App\Jobs;

use App\Repositories\PedidoRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/* Cancela pedidos que ficaram "pendente" por mais de 24 horas. */
class CancelarPedidosPendentesJob implements ShouldQueue
{
    use Queueable;

    public const HORAS_LIMITE = 24;

    public function handle(PedidoRepository $pedidos): void
    {
        $qtd = $pedidos->cancelarPendentesAnterioresA(now()->subHours(self::HORAS_LIMITE));

        Log::info("CancelarPedidosPendentesJob: {$qtd} pedido(s) cancelado(s).");
    }
}
