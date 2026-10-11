<?php

namespace App\Services;

use App\Repositories\FormaPagamentoRepository;

class FormaPagamentoService extends BaseService
{
    public function __construct(FormaPagamentoRepository $repository)
    {
        parent::__construct($repository);
    }
}
