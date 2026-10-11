<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_preco_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('tbl_item')->cascadeOnDelete();
            $table->decimal('preco', 10, 2);
            $table->date('vigencia_inicio');
            $table->date('vigencia_fim')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->index(['item_id', 'vigencia_inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_preco_item');
    }
};
