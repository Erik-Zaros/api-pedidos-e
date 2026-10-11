<?php

namespace App\Services;

use App\Repositories\PrecoItemRepository;

class PrecoItemService extends BaseService
{
    public function __construct(PrecoItemRepository $repository)
    {
        parent::__construct($repository);
    }
}
