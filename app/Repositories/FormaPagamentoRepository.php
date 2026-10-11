<?php

namespace App\Repositories;

use App\Models\FormaPagamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class FormaPagamentoRepository extends BaseRepository
{
    public function __construct(FormaPagamento $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->orderByDesc('id')->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->query()->findOrFail($id);
    }
}
