<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PrecoItemController;
use App\Http\Controllers\TipoPedidoController;
use Illuminate\Support\Facades\Route;

/* autenticação */
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

/* rotas protegidas pela autenticação */
Route::middleware('auth:api')->group(function () {
    Route::apiResource('tipos-pedido', TipoPedidoController::class)->parameters(['tipos-pedido' => 'id']);
    Route::apiResource('formas-pagamento', FormaPagamentoController::class)->parameters(['formas-pagamento' => 'id']);
    Route::apiResource('itens', ItemController::class)->parameters(['itens' => 'id']);
    Route::apiResource('precos-item', PrecoItemController::class)->parameters(['precos-item' => 'id']);

    Route::post('pedidos/{id}/cancelar', [PedidoController::class, 'cancelar']);
    Route::apiResource('pedidos', PedidoController::class)->parameters(['pedidos' => 'id']);
});
