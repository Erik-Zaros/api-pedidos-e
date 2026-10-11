<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    public function __construct(private ItemService $service)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return ItemResource::collection(
            $this->service->listar(min(max($request->integer('per_page', 15), 1), 100))
        );
    }

    public function store(StoreItemRequest $request): JsonResponse
    {
        $model = $this->service->criar($request->validated());

        return (new ItemResource($model))->response()->setStatusCode(201);
    }

    public function show(int $id): ItemResource
    {
        return new ItemResource($this->service->buscar($id));
    }

    public function update(UpdateItemRequest $request, int $id): ItemResource
    {
        return new ItemResource($this->service->atualizar($id, $request->validated()));
    }

    public function destroy(int $id): Response
    {
        $this->service->excluir($id);

        return response()->noContent();
    }
}
