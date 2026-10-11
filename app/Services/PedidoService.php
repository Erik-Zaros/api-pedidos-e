<?php

namespace App\Services;

use App\Jobs\ProcessarPedidoJob;
use App\Models\Pedido;
use App\Repositories\ItemRepository;
use App\Repositories\PedidoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PedidoService
{
    public function __construct(
        private PedidoRepository $pedidos,
        private ItemRepository $itens,
    ) {
    }

    public function listar(int $userId, int $perPage = 15, ?string $status = null): LengthAwarePaginator
    {
        return $this->pedidos->paginarDoUsuario($userId, $perPage, $status);
    }

    public function buscar(int $id, int $userId): Pedido
    {
        return $this->pedidos->findDoUsuarioOrFail($id, $userId);
    }

    public function criar(array $data, int $userId): Pedido
    {
        return DB::transaction(function () use ($data, $userId) {
            [$linhas, $total] = $this->montarLinhas($data['itens']);

            $pedido = $this->pedidos->create([
                'user_id' => $userId,
                'tipo_pedido' => $data['tipo_pedido'],
                'forma_pagamento' => $data['forma_pagamento'],
                'observacao' => $data['observacao'] ?? null,
                'status' => Pedido::STATUS_PENDENTE,
                'total' => $total,
            ]);

            $this->pedidos->salvarItens($pedido, $linhas);

            ProcessarPedidoJob::dispatch($pedido->id)->afterCommit();

            return $this->pedidos->carregarRelacoes($pedido);
        });
    }

    public function atualizar(int $id, array $data, int $userId): Pedido
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $pedido = $this->pedidos->findDoUsuarioOrFail($id, $userId);

            if ($pedido->status === Pedido::STATUS_CANCELADO) {
                throw ValidationException::withMessages(['status' => 'Pedido cancelado não pode ser alterado.']);
            }

            if (isset($data['itens'])) {
                if ($pedido->status !== Pedido::STATUS_PENDENTE) {
                    throw ValidationException::withMessages(['itens' => 'Só é possível alterar os itens de pedidos pendentes.']);
                }

                [$linhas, $total] = $this->montarLinhas($data['itens']);
                $this->pedidos->removerItens($pedido);
                $this->pedidos->salvarItens($pedido, $linhas);
                $data['total'] = $total;
            }

            unset($data['itens']);
            $this->pedidos->update($pedido, $data);

            return $this->pedidos->carregarRelacoes($pedido->refresh());
        });
    }

    public function cancelar(int $id, int $userId): Pedido
    {
        $pedido = $this->pedidos->findDoUsuarioOrFail($id, $userId);

        if ($pedido->status === Pedido::STATUS_ENTREGUE) {
            throw ValidationException::withMessages(['status' => 'Pedido já entregue não pode ser cancelado.']);
        }

        $this->pedidos->update($pedido, ['status' => Pedido::STATUS_CANCELADO]);

        return $this->pedidos->carregarRelacoes($pedido->refresh());
    }

    public function excluir(int $id, int $userId): void
    {
        $pedido = $this->pedidos->findDoUsuarioOrFail($id, $userId);
        $this->pedidos->delete($pedido);
    }

    private function montarLinhas(array $itensRequest): array
    {
        $itens = $this->itens->findManyComPrecos(array_column($itensRequest, 'item_id'));

        $linhas = [];
        $total = 0.0;

        foreach ($itensRequest as $i => $linha) {
            $item = $itens->get($linha['item_id']);
            $preco = $item?->precoVigente();

            if (! $preco) {
                throw ValidationException::withMessages([
                    "itens.$i.item_id" => "O item \"{$item?->nome}\" não possui preço vigente cadastrado.",
                ]);
            }

            $subtotal = round((float) $preco->preco * $linha['quantidade'], 2);
            $total += $subtotal;

            $linhas[] = [
                'item_id' => $item->id,
                'quantidade' => $linha['quantidade'],
                'preco_unitario' => $preco->preco,
                'subtotal' => $subtotal,
            ];
        }

        return [$linhas, round($total, 2)];
    }
}
