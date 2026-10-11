<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePedidoRequest;
use App\Http\Requests\UpdatePedidoRequest;
use App\Http\Resources\PedidoResource;
use App\Models\Pedido;
use App\Services\PedidoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{
    public function __construct(private PedidoService $service)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate(['status' => ['nullable', Rule::in(Pedido::STATUS)]]);

        return PedidoResource::collection($this->service->listar(
            $request->user()->id,
            min(max($request->integer('per_page', 15), 1), 100),
            $request->query('status'),
        ));
    }

    public function store(StorePedidoRequest $request): JsonResponse
    {
        $pedido = $this->service->criar($request->validated(), $request->user()->id);

        return (new PedidoResource($pedido))->response()->setStatusCode(201);
    }

    public function show(Request $request, int $id): PedidoResource
    {
        return new PedidoResource($this->service->buscar($id, $request->user()->id));
    }

    public function update(UpdatePedidoRequest $request, int $id): PedidoResource
    {
        return new PedidoResource(
            $this->service->atualizar($id, $request->validated(), $request->user()->id)
        );
    }

    public function cancelar(Request $request, int $id): PedidoResource
    {
        return new PedidoResource($this->service->cancelar($id, $request->user()->id));
    }

    public function destroy(Request $request, int $id): Response
    {
        $this->service->excluir($id, $request->user()->id);

        return response()->noContent();
    }
}
