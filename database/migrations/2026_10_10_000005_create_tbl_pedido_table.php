<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // tbl_pedido.tipo_pedido -> tbl_tipo_pedido.id
            $table->foreignId('tipo_pedido')->constrained('tbl_tipo_pedido');
            // tbl_pedido.forma_pagamento -> tbl_forma_pagamento.id
            $table->foreignId('forma_pagamento')->constrained('tbl_forma_pagamento');
            $table->string('status', 20)->default('pendente');
            $table->decimal('total', 10, 2)->default(0);
            $table->string('observacao')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_pedido');
    }
};
