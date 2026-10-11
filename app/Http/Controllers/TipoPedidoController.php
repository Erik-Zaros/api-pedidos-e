<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoPedidoRequest;
use App\Http\Requests\UpdateTipoPedidoRequest;
use App\Http\Resources\TipoPedidoResource;
use App\Services\TipoPedidoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TipoPedidoController extends Controller
{
    public function __construct(private TipoPedidoService $service)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return TipoPedidoResource::collection(
            $this->service->listar(min(max($request->integer('per_page', 15), 1), 100))
        );
    }

    public function store(StoreTipoPedidoRequest $request): JsonResponse
    {
        $model = $this->service->criar($request->validated());

        return (new TipoPedidoResource($model))->response()->setStatusCode(201);
    }

    public function show(int $id): TipoPedidoResource
    {
        return new TipoPedidoResource($this->service->buscar($id));
    }

    public function update(UpdateTipoPedidoRequest $request, int $id): TipoPedidoResource
    {
        return new TipoPedidoResource($this->service->atualizar($id, $request->validated()));
    }

    public function destroy(int $id): Response
    {
        $this->service->excluir($id);

        return response()->noContent();
    }
}
