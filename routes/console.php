<?php

use App\Jobs\CancelarPedidosPendentesJob;
use Illuminate\Support\Facades\Schedule;

/* cancela pedidos pendente mais de 24h. Executa com php artisan schedule:work*/
Schedule::job(new CancelarPedidosPendentesJob)->hourly();
