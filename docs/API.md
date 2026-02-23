# API v1 - Saldo de Estoque

## Auth
Todos os endpoints de `v1` usam `auth:sanctum`.

### Login (gera token)
```bash
curl -X POST "http://localhost:8000/api/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"usuario@example.com","password":"Secret123!"}'
```

## Endpoints

### Listar saldo de estoque
`GET /api/v1/saldo-estoque`

Parâmetros de query:
- `page` (default `1`)
- `per_page` (default `15`, max `100`)
- `sort`: `id|quantidade|created_at|updated_at|sku|descricao|posicao|unidade_id`
- `direction`: `asc|desc`
- `sku`
- `material`
- `descricao`
- `unidade`
- `posicao`
- `min_qtd`
- `max_qtd`
- `updated_from`
- `updated_to`

Exemplo:
```bash
curl "http://localhost:8000/api/v1/saldo-estoque?sku=ABC&min_qtd=10&sort=updated_at&direction=desc&per_page=20" \
  -H "Authorization: Bearer <TOKEN>"
```

### Detalhar saldo por ID
`GET /api/v1/saldo-estoque/{id}`

```bash
curl "http://localhost:8000/api/v1/saldo-estoque/1" \
  -H "Authorization: Bearer <TOKEN>"
```

### Atualizar saldo (opcional no fluxo)
`PUT/PATCH /api/v1/saldo-estoque/{id}`

Campos aceitos:
- `quantidade`
- `data_entrada`

```bash
curl -X PATCH "http://localhost:8000/api/v1/saldo-estoque/1" \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" \
  -d '{"quantidade": 150}'
```
