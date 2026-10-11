# API Pedidos (api-pedidos-e)

API REST em **Laravel** para um sistema de pedidos, com autenticação **JWT**.

## Padrão de camadas

`Request → Controller → Service → Repository → Model` e a resposta sai por um `Resource`.

| Camada | Pasta | Responsabilidade |
|---|---|---|
| Request | `app/Http/Requests` | Validação da entrada |
| Controller | `app/Http/Controllers` | Recebe a requisição e devolve o Resource |
| Service | `app/Services` | Regras de negócio |
| Repository | `app/Repositories` | Acesso ao banco (Eloquent) |
| Resource | `app/Http/Resources` | Formato do JSON de saída |

## Modelos e relacionamentos (7 modelos)

- `User` 1—N `Pedido`
- `TipoPedido` (`tbl_tipo_pedido`) 1—N `Pedido` (coluna `tbl_pedido.tipo_pedido`)
- `FormaPagamento` (`tbl_forma_pagamento`) 1—N `Pedido` (coluna `tbl_pedido.forma_pagamento`)
- `Pedido` (`tbl_pedido`) 1—N `PedidoItem` (`tbl_pedido_item`)
- `Item` (`tbl_item`) 1—N `PedidoItem`
- `Item` 1—N `PrecoItem` (`tbl_preco_item`, preços com vigência)

O preço usado no pedido é o **preço vigente** do item (ativo, `vigencia_inicio <= hoje` e `vigencia_fim` nula ou `>= hoje`). Ele é "congelado" em `tbl_pedido_item.preco_unitario`.

## CRUDs completos
`tipos-pedido`, `formas-pagamento`, `itens`, `precos-item` e `pedidos`.

## Jobs
- `ProcessarPedidoJob`: disparado ao criar um pedido; recalcula o total e muda o status `pendente → confirmado`.
- `CancelarPedidosPendentesJob`: agendado de hora em hora; cancela pedidos pendentes há mais de 24h.

## Como rodar

Requisitos: PHP 8.2+, Composer, extensão `pdo_pgsql` e um PostgreSQL rodando.

Banco de dados (PostgreSQL): crie o banco `api_pedidos` e ajuste usuário/senha no `.env`.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan serve
```

Em outros terminais (para os jobs funcionarem):

```bash
php artisan queue:work      # processa ProcessarPedidoJob
php artisan schedule:work   # roda o job agendado
```

> Atalho: `composer setup` faz instalação, .env, chaves e migrate --seed.

Usuário criado pelo seed: `admin@teste.com` / `secret123`

## Endpoints (prefixo `/api`)

| Método | Rota | Auth |
|---|---|---|
| POST | `/auth/register` | não |
| POST | `/auth/login` | não |
| GET | `/auth/me` | sim |
| POST | `/auth/logout` | sim |
| POST | `/auth/refresh` | sim |
| GET/POST | `/tipos-pedido` · GET/PUT/DELETE `/tipos-pedido/{id}` | sim |
| GET/POST | `/formas-pagamento` · GET/PUT/DELETE `/formas-pagamento/{id}` | sim |
| GET/POST | `/itens` · GET/PUT/DELETE `/itens/{id}` | sim |
| GET/POST | `/precos-item` · GET/PUT/DELETE `/precos-item/{id}` | sim |
| GET/POST | `/pedidos` · GET/PUT/DELETE `/pedidos/{id}` | sim |
| POST | `/pedidos/{id}/cancelar` | sim |

Listagens aceitam `?per_page=` e `/pedidos` aceita `?status=`.
Cada usuário enxerga apenas os **próprios** pedidos.

## Exemplos

```bash
# login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"admin@teste.com","password":"secret123"}'

# criar preço para um item
curl -X POST http://localhost:8000/api/precos-item \
  -H "Authorization: Bearer SEU_TOKEN" -H "Content-Type: application/json" \
  -d '{"item_id":1,"preco":25.90,"vigencia_inicio":"2026-10-10"}'

# criar pedido
curl -X POST http://localhost:8000/api/pedidos \
  -H "Authorization: Bearer SEU_TOKEN" -H "Content-Type: application/json" \
  -d '{
    "tipo_pedido": 1,
    "forma_pagamento": 2,
    "observacao": "Sem cebola",
    "itens": [
      {"item_id": 1, "quantidade": 2},
      {"item_id": 3, "quantidade": 1}
    ]
  }'
```
