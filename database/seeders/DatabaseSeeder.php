<?php

namespace Database\Seeders;

use App\Models\FormaPagamento;
use App\Models\Item;
use App\Models\PrecoItem;
use App\Models\TipoPedido;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@teste.com'],
            ['name' => 'Administrador', 'password' => 'secret123']
        );

        foreach (['Balcão', 'Delivery', 'Mesa', 'Retirada'] as $nome) {
            TipoPedido::firstOrCreate(['nome' => $nome]);
        }

        foreach (['Dinheiro', 'Pix', 'Cartão de Crédito', 'Cartão de Débito'] as $nome) {
            FormaPagamento::firstOrCreate(['nome' => $nome]);
        }

        $itens = [
            ['X-Burger', 'Pão, hambúrguer, queijo e salada', 22.90],
            ['Batata Frita', 'Porção média', 15.00],
            ['Refrigerante Lata', '350ml', 6.50],
            ['Suco Natural', '500ml', 9.00],
        ];

        foreach ($itens as [$nome, $descricao, $preco]) {
            $item = Item::firstOrCreate(['nome' => $nome], ['descricao' => $descricao]);

            if (! $item->precos()->exists()) {
                PrecoItem::create([
                    'item_id' => $item->id,
                    'preco' => $preco,
                    'vigencia_inicio' => now()->subDay()->toDateString(),
                ]);
            }
        }
    }
}
