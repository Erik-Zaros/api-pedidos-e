<?php

namespace App\Repositories;

use App\Models\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ItemRepository extends BaseRepository
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->with('precos')->orderByDesc('id')->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->query()->with('precos')->findOrFail($id);
    }

    /** Itens (com preços carregados) indexados pelo id. */
    public function findManyComPrecos(array $ids): Collection
    {
        return $this->query()->with('precos')->whereIn('id', $ids)->get()->keyBy('id');
    }
}
