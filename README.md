Teste Técnico -- Aplicação de Gestão de Projetos
===============================================

Este repositório contém uma aplicação web para gestão de projetos. A aplicação oferece um sistema de autenticação de usuários e um módulo completo para gerenciar projetos, incluindo operações de criação, leitura, edição e desativação (CRUD).

🚀 Tecnologias Utilizadas
-------------------------

### Backend:

-   PHP 8.2

-   Laravel 12

-   SQLite

-   Migrations e Seeders

-   PHPUnit

### Frontend:

-   Vue.js 3

-   Inertia.js

-   Tailwind CSS

-   `vue-multiselect` (para seleção de membros da equipe)

-   `Heroicons` (para ícones)

✨ Funcionalidades Implementadas
-------------------------------

### Autenticação

-   **Registro de Usuários:** Permite que novos usuários criem uma conta.

-   **Login de Usuários:** Permite que usuários existentes acessem o sistema.

-   **Proteção de Rotas:** Todas as rotas relacionadas à gestão de projetos são protegidas, acessíveis apenas por usuários autenticados.

### Módulo de Projetos

#### Listagem de Projetos:

-   Interface responsiva e otimizada para diferentes tamanhos de tela.

-   Filtros avançados por Título, Nome do Cliente, Fase e Status (Ativo/Inativo), com a lógica de filtragem aplicada no back-end.

-   Exibição clara do Título, Cliente, Fase (com cores indicativas), Status, Criador e Membros da Equipe.

-   Botão de "Ações" (dropdown) por projeto, permitindo "Editar" e "Desativar".

-   Escolhido dropdown pensando em "funcionalidades futuras".

#### Criação de Novos Projetos:

-   Formulário intuitivo com layout em duas colunas.

-   Campos para Título, Nome do Cliente, Fase e seleção de Membros da Equipe.

-   Campo "Membros da Equipe" com funcionalidade de busca e seleção múltipla (`vue-multiselect`).

#### Edição de Projetos Existentes:

-   Modal de edição que pré-preenche os dados do projeto selecionado.

-   Permite atualizar todos os campos.

-   O `vue-multiselect` funciona dentro da modal, com dropdown visível e scroll interno.

#### Desativação de Projetos:

-   Modal de confirmação simples para desativação lógica (o projeto muda de `is_active = true` para `false` no banco de dados, sem ser removido).

### Regras de Negócio

-   **Visibilidade de Projetos:** Cada usuário pode visualizar apenas os projetos que ele criou ou os projetos nos quais ele está alocado como membro da equipe.

### Testes Automatizados

-   Testes de recurso foram implementados utilizando PHPUnit para garantir a correta funcionalidade das operações de CRUD de projetos, e as regras de negócio de visibilidade.

⚙️ Instalação e Execução do Projeto
-----------------------------------

Siga os passos abaixo para configurar e executar o projeto em sua máquina.

### Pré-requisitos

-   PHP (versão 8.2 ou superior)

-   Composer

-   Node.js (LTS recomendado)

-   NPM ou Yarn

-   Git

### 1\. Clonar o Repositório

```
git clone https://github.com/willian0017/gestao-de-projetos.git
cd project-manager
```

### 2\. Configuração do Backend (Laravel)

#### Instalar Dependências PHP:

```
composer install
```

#### Copiar o Arquivo de Ambiente:

```
cp .env.example .env
```

#### Gerar a Chave da Aplicação:

```
php artisan key:generate
```

#### Configurar o Banco de Dados (SQLite):

Abra o arquivo `.env` e certifique-se de que as configurações do banco de dados estão apontando para SQLite:

```
DB_CONNECTION=sqlite
DB_DATABASE=./database/database.sqlite
```

Crie o arquivo do banco de dados (se não existir):

```
touch database/database.sqlite
```

#### Executar Migrações e Seeders:

Isso criará as tabelas no banco de dados e populará com dados de teste (incluindo usuários e alguns projetos).

```
php artisan migrate --seed
```

-   **Usuário de Teste:** Um usuário padrão será criado (verifique `database/seeders/UserSeeder.php` ou `DatabaseSeeder.php` para credenciais, `admin@projeto.com` / `password`).

### 3\. Configuração do Frontend (Vue.js / Inertia.js)

#### Instalar Dependências JavaScript:

```
npm install
# ou yarn install
```

#### Compilar os Assets Frontend:

Para desenvolvimento:

```
npm run dev
# ou yarn dev
```

-   **Importante:** Mantenha `npm run dev` rodando em um terminal separado durante o desenvolvimento para que as mudanças no frontend sejam refletidas automaticamente.

### 4\. Executar a Aplicação

#### Iniciar o Servidor Laravel:

```
php artisan serve
```

#### Acessar no Navegador:

Abra seu navegador e acesse a URL fornecida pelo `php artisan serve` (geralmente `http://127.0.0.1:8000`).

-   **Login:** Use as credenciais do usuário criado pelo seeder (ex: `admin@projeto.com` / `password`).

### 5\. Executar Testes Automatizados

Para executar os testes de funcionalidade:

```
php artisan test
```

🌐 Ambiente de Desenvolvimento
------------------------------

Este projeto foi desenvolvido utilizando:

-   **Sistema Operacional:** Windows 11 com WSL.

-   **Editor de Código:** VS Code.
