<div align="center">

Sistema de Controle Financeiro Pessoal

<p>Um sistema web completo para gestão de finanças pessoais, desenvolvido com foco em usabilidade, segurança e análise de dados.</p>

</div>

Visão Geral do Projeto

Este projeto foi desenvolvido como parte da disciplina de Tópicos Especiais, visando solucionar a dificuldade comum de organização financeira. O sistema permite o controle total de receitas e despesas, categorização inteligente e gestão de metas de economia através de Cofrinhos.

Funcionalidades Principais

Dashboard Analítico: Painel visual com cartões de resumo (Entradas, Saídas, Saldo) e gráficos interativos para análise rápida.

Gestão de Transações: CRUD completo de receitas e despesas, com validações de segurança e vínculo automático ao usuário logado.

Cofrinhos (Metas): Funcionalidade exclusiva para criar objetivos de economia. Ao depositar no cofrinho, o saldo da conta principal é debitado automaticamente (e vice-versa), garantindo integridade contábil.

Categorização: Sistema flexível para criar e gerir categorias personalizadas (ex: Alimentação, Transporte).

Relatórios Visuais: Integração com Chart.js para visualização de gastos por categoria.

Interface: Design limpo, responsivo e profissional, construído com Tailwind CSS.

Segurança: Autenticação robusta, proteção CSRF e validação de propriedade de dados.

Tecnologias Utilizadas

O projeto foi construído utilizando uma stack moderna e robusta:

Backend:

Laravel 12 (Framework PHP)

Eloquent ORM

Banco de Dados:

PostgreSQL

Frontend:

Blade Templates

Tailwind CSS

Alpine.js

Chart.js

SweetAlert2

Ferramentas de Desenvolvimento:

Laravel DebugBar

Vite

Instalação e Configuração

Siga os passos abaixo para rodar o projeto na sua máquina local.

Pré-requisitos

PHP 8.2 ou superior

Composer

Node.js & NPM

PostgreSQL

Passo a Passo

Clone o repositório:

git clone [https://github.com/SEU-USUARIO/controle-financeiro.git](https://github.com/SEU-USUARIO/controle-financeiro.git)
cd controle-financeiro


Instale as dependências do Backend (PHP):

composer install


Instale as dependências do Frontend (JS/CSS):

npm install


Configure o ambiente:

Duplique o arquivo .env.example e renomeie para .env.

Configure as credenciais do seu banco de dados PostgreSQL:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=controle_financeiro
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha


Gere a chave da aplicação:

php artisan key:generate


Execute as migrações:

php artisan migrate


Inicie o projeto:
Você precisará de dois terminais abertos:

Terminal 1 (Servidor PHP):

php artisan serve


Terminal 2 (Compilador de Assets):

npm run dev


Acesse: Abra http://127.0.0.1:8000 no seu navegador.

Estrutura do Banco de Dados

O projeto utiliza relacionamentos relacionais para garantir a integridade dos dados:

Users: Tabela principal de autenticação.

Categorias: Ligada ao Usuário (1:N).

Transações: Ligada ao Usuário (1:N) e à Categoria (1:N).

Cofrinhos: Ligada ao Usuário (1:N).

Movimentações_Cofrinho: Histórico de depósitos/retiradas ligado ao Cofrinho (1:N).

Desenvolvido por Gabriel.
