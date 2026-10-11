<?php

namespace App\Repositories;

use App\Models\Pedido;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PedidoRepository extends BaseRepository
{
    private const RELACOES = ['tipoPedido', 'formaPagamento', 'itens.item'];

    public function __construct(Pedido $model)
    {
        parent::__construct($model);
    }

    /** Pedidos do usuário logado, com filtro opcional por status. */
    public function paginarDoUsuario(int $userId, int $perPage = 15, ?string $status = null): LengthAwarePaginator
    {
        return $this->query()
            ->with(self::RELACOES)
            ->where('user_id', $userId)
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findDoUsuarioOrFail(int $id, int $userId): Pedido
    {
        return $this->query()->with(self::RELACOES)->where('user_id', $userId)->findOrFail($id);
    }

    public function find(int $id): ?Pedido
    {
        return $this->query()->find($id);
    }

    public function salvarItens(Pedido $pedido, array $linhas): void
    {
        $pedido->itens()->createMany($linhas);
    }

    public function removerItens(Pedido $pedido): void
    {
        $pedido->itens()->delete();
    }

    public function carregarRelacoes(Pedido $pedido): Pedido
    {
        return $pedido->load(self::RELACOES);
    }

    /** Cancela, em lote, pedidos pendentes criados antes da data informada. */
    public function cancelarPendentesAnterioresA(\DateTimeInterface $limite): int
    {
        return $this->query()
            ->where('status', Pedido::STATUS_PENDENTE)
            ->where('created_at', '<', $limite)
            ->update(['status' => Pedido::STATUS_CANCELADO]);
    }
}
