<?php

namespace App\Services;

use App\Repositories\TipoPedidoRepository;

class TipoPedidoService extends BaseService
{
    public function __construct(TipoPedidoRepository $repository)
    {
        parent::__construct($repository);
    }
}
