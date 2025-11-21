# Maria Cavalheiro CRM de Leads

CRM simples em PHP (PDO + MySQL) e Tailwind para organizar leads de atacado. Inclui autenticação, kanban com stages por usuário, drag-and-drop de cards e modais para criar/editar leads.

## Estrutura
- `public/index.php`: front controller e roteador simples via `route`.
- `app/Controllers`: controllers para autenticação, dashboard, leads, stages e perfil.
- `app/Models`: models com acesso via PDO (User, Lead, Stage, PasswordReset).
- `app/Views`: views em PHP com Tailwind, incluindo landing page pública, telas de auth, dashboard/kanban e perfil.
- `config/`: configuração e conexão de banco.
- `database.sql`: script para criação do schema completo.

## Instalação
1. **Clone e instale dependências básicas**
   - Requer PHP 8+ com extensão PDO MySQL e um servidor MySQL.

2. **Configure o banco**
   - Importe `database.sql` no MySQL: `mysql -u root -p < database.sql`.
   - Ajuste as credenciais em `config/config.php` (host, database, user, password, charset).

3. **Configurar servidor web**
   - Aponte o docroot para `public/` (ou rode `php -S localhost:8000 -t public`).

4. **Fluxo de uso**
   - Acesse a landing em `/public/index.php`.
   - Crie uma conta (papéis: `user` vê apenas leads próprios; `admin` pode ver todos).
   - O dashboard inicial cria stages padrão para o usuário.
   - Crie/edite leads via modais; arraste os cards entre colunas para atualizar o stage.
   - Use "Configurar Kanban" para renomear e ordenar stages (exclusão só se estiver vazia).
   - Perfil permite alterar nome e senha.

## Segurança e boas práticas
- Senhas com `password_hash`/`password_verify`.
- Sessões PHP para autenticação.
- Prepared statements PDO para prevenir SQL Injection.
- `htmlspecialchars` aplicado em saídas principais.

## Licença
Uso livre para fins de estudo e customização.
