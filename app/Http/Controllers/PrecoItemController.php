<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrecoItemRequest;
use App\Http\Requests\UpdatePrecoItemRequest;
use App\Http\Resources\PrecoItemResource;
use App\Services\PrecoItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PrecoItemController extends Controller
{
    public function __construct(private PrecoItemService $service)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return PrecoItemResource::collection(
            $this->service->listar(min(max($request->integer('per_page', 15), 1), 100))
        );
    }

    public function store(StorePrecoItemRequest $request): JsonResponse
    {
        $model = $this->service->criar($request->validated());

        return (new PrecoItemResource($model))->response()->setStatusCode(201);
    }

    public function show(int $id): PrecoItemResource
    {
        return new PrecoItemResource($this->service->buscar($id));
    }

    public function update(UpdatePrecoItemRequest $request, int $id): PrecoItemResource
    {
        return new PrecoItemResource($this->service->atualizar($id, $request->validated()));
    }

    public function destroy(int $id): Response
    {
        $this->service->excluir($id);

        return response()->noContent();
    }
}
