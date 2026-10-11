<?php

namespace App\Repositories;

use App\Models\PrecoItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class PrecoItemRepository extends BaseRepository
{
    public function __construct(PrecoItem $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->with('item')->orderByDesc('id')->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->query()->with('item')->findOrFail($id);
    }
}
