<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormaPagamentoRequest;
use App\Http\Requests\UpdateFormaPagamentoRequest;
use App\Http\Resources\FormaPagamentoResource;
use App\Services\FormaPagamentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class FormaPagamentoController extends Controller
{
    public function __construct(private FormaPagamentoService $service)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return FormaPagamentoResource::collection(
            $this->service->listar(min(max($request->integer('per_page', 15), 1), 100))
        );
    }

    public function store(StoreFormaPagamentoRequest $request): JsonResponse
    {
        $model = $this->service->criar($request->validated());

        return (new FormaPagamentoResource($model))->response()->setStatusCode(201);
    }

    public function show(int $id): FormaPagamentoResource
    {
        return new FormaPagamentoResource($this->service->buscar($id));
    }

    public function update(UpdateFormaPagamentoRequest $request, int $id): FormaPagamentoResource
    {
        return new FormaPagamentoResource($this->service->atualizar($id, $request->validated()));
    }

    public function destroy(int $id): Response
    {
        $this->service->excluir($id);

        return response()->noContent();
    }
}
