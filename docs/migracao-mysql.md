# Migracao Para MySQL

Este projeto continua usando SQLite como padrao local. A migracao para MySQL deve ser feita quando houver um banco criado, usuario definido e uma janela segura para validar dados.

## Checklist

1. Criar backup do SQLite atual:
   `cp database/database.sqlite database/database.backup.sqlite`

2. Criar o banco MySQL com charset recomendado:
   `utf8mb4` e collation `utf8mb4_unicode_ci`.

3. Atualizar o `.env` do ambiente alvo usando `.env.mysql.example` como referencia.

4. Limpar cache de configuracao:
   `php artisan config:clear`

5. Rodar migrations no MySQL:
   `php artisan migrate --database=mysql`

6. Migrar dados do SQLite para MySQL com ferramenta apropriada ao ambiente.

7. Validar rotas, login e APIs principais:
   `php artisan route:list`
   `php artisan test`

## Pontos De Atencao

- Nao alterar migrations ja usadas em producao sem criar uma nova migration corretiva.
- Conferir campos com tipos sensiveis a banco, como `json`, `text`, datas e indices compostos.
- Validar chaves estrangeiras depois da importacao, pois MySQL aplica restricoes de forma mais rigida.
- Manter `DB_CONNECTION=sqlite` em desenvolvimento ate o MySQL estar disponivel.
