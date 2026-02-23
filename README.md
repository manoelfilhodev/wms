# WMS - Warehouse Management System

Sistema de gestão logística e estoque construído com Laravel, com módulos web e API versionada (`v1`).

## Visão Geral

O projeto cobre operações de recebimento, armazenagem, separação, inventário, demandas e controle de saldo de estoque.

Principais objetivos:
- Controle operacional do WMS via interface web.
- Exposição gradual de funcionalidades via API-first.
- Base preparada para autenticação por token com Sanctum.
- Documentação navegável da API em `/api/docs`.

## Stack Técnica

- PHP `^8.2`
- Laravel `^11.31`
- Laravel Sanctum
- MySQL
- Blade + assets frontend
- Scribe (documentação da API)

## Estrutura Principal

```text
app/
  Http/
    Controllers/
    Middleware/
  Models/
  Traits/
config/
database/
  migrations/
  seeders/
routes/
  web.php
  api.php
resources/
  views/
docs/
  API.md
```

## Requisitos

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js/NPM (para assets, quando necessário)

## Instalação

```bash
git clone https://github.com/manoelfilhodev/wms.git
cd wms
composer install
cp .env.example .env
php artisan key:generate
```

## Configuração de Ambiente

Ajuste no `.env`:

```dotenv
APP_NAME="Systex WMS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

API_DOCS_ENABLED=false
```

## Banco de Dados

```bash
php artisan migrate
# opcional (se o ambiente exigir dados iniciais)
php artisan db:seed
```

## Executar o Projeto

```bash
php artisan serve
```

Acesso web local:
- `http://127.0.0.1:8000`

## API v1

Base de rotas:
- `http://127.0.0.1:8000/api/v1`

Endpoints principais atuais:
- `POST /api/v1/auth/login`
- `GET /api/v1/me`
- `GET /api/v1/saldo-estoque`
- `GET /api/v1/saldo-estoque/{id}`
- `PUT/PATCH /api/v1/saldo-estoque/{id}`

Autenticação:
- Bearer Token (`Authorization: Bearer <token>`)

## Documentação da API (Scribe)

Gerar documentação:

```bash
php artisan config:clear
php artisan scribe:generate
```

Acessar documentação:
- `http://127.0.0.1:8000/api/docs`
- `http://127.0.0.1:8000/api/docs.postman`
- `http://127.0.0.1:8000/api/docs.openapi`

Regras de acesso:
- Em `APP_ENV=local`: acesso liberado.
- Fora de local: `/api/docs` retorna `404`, exceto se `API_DOCS_ENABLED=true`.

## Testes

```bash
php artisan test
```

## Qualidade e Padrões

Diretrizes adotadas no projeto:
- Clean Code
- Separação por responsabilidade
- Validação por `FormRequest`
- Saída de API padronizada
- Evolução por PRs pequenos e objetivos

## Troubleshooting

Se a UI da documentação exibir `Failed to fetch`:
1. Confirme `APP_URL=http://127.0.0.1:8000` no `.env`.
2. Rode `php artisan config:clear`.
3. Rode `php artisan scribe:generate`.
4. Reabra `/api/docs`.

## Contribuição

Fluxo sugerido:
1. Criar branch de feature/fix.
2. Commits pequenos e semânticos.
3. Abrir PR com evidências de validação (`test`, `migrate`, `route:list` quando aplicável).

---

Para exemplos de uso da API com `curl`, consulte `docs/API.md`.
