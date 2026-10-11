<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

abstract class BaseService
{
    public function __construct(protected BaseRepository $repository)
    {
    }

    public function listar(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function buscar(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function criar(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function atualizar(int $id, array $data): Model
    {
        return $this->repository->update($this->repository->findOrFail($id), $data);
    }

    public function excluir(int $id): bool
    {
        $model = $this->repository->findOrFail($id);

        try {
            return $this->repository->delete($model);
        } catch (QueryException) {
            abort(409, 'Registro em uso e não pode ser excluído.');
        }
    }
}
