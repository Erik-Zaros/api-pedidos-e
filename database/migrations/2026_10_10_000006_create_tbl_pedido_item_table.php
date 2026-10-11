<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_pedido_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('tbl_pedido')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('tbl_item');
            $table->unsignedInteger('quantidade');
            $table->decimal('preco_unitario', 10, 2); // preço "congelado" no momento do pedido
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_pedido_item');
    }
};
