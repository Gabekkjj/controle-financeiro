<div align="center">

# SISTEMA DE CONTROLE FINANCEIRO PESSOAL

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)

<p>
    Uma solução robusta e minimalista para gestão financeira, desenvolvida com foco em integridade de dados, 
    segurança e experiência do usuário. Projeto acadêmico da disciplina de Tópicos Especiais.
</p>

<br>

<a href="#sobre">Sobre</a> •
<a href="#funcionalidades">Funcionalidades</a> •
<a href="#tecnologias">Tecnologias</a> •
<a href="#instalacao">Instalação</a> •
<a href="#database">Modelagem</a>

</div>

<hr>

<h2 id="sobre">Sobre o Projeto</h2>

O Sistema de Controle Financeiro foi projetado para resolver a complexidade da gestão de finanças pessoais. Diferente de planilhas estáticas, esta aplicação oferece um ambiente dinâmico onde cada transação afeta o saldo global em tempo real.

O diferencial técnico do projeto reside na implementação do módulo de **Cofrinhos**, que utiliza transações de banco de dados (DB Transactions) para garantir que o dinheiro movido para uma reserva seja debitado contabilmente da conta principal, mantendo a consistência financeira do sistema.

---

<h2 id="funcionalidades">Funcionalidades Principais</h2>

### Gestão Financeira
* **Dashboard Analítico:** Visão macroeconômica com cartões de resumo (Entradas, Saídas, Saldo) e gráficos interativos para análise de tendências.
* **Controle de Transações:** Sistema completo (CRUD) para registro de receitas e despesas, com validação estrita de tipos e categorias.

### Módulo de Economia (Cofrinhos)
* **Metas Personalizadas:** Criação de objetivos financeiros com definição de valores-alvo.
* **Movimentação Segura:** Lógica de depósito e retirada que interage diretamente com o saldo do usuário, prevenindo inconsistências (ex: retirar mais do que o saldo disponível).

### Organização e Segurança
* **Categorização:** Sistema flexível para criação e edição de categorias financeiras.
* **Relatórios Visuais:** Integração com bibliotecas gráficas para visualização de despesas por categoria.
* **Segurança de Dados:** Implementação de Policies e Gates para garantir que usuários acessem apenas os seus próprios registros.

---

<h2 id="tecnologias">Stack Tecnológica</h2>

O projeto foi construído seguindo os padrões modernos de desenvolvimento web, utilizando a arquitetura MVC.

<table>
  <tr>
    <td align="center" width="100">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" width="40" height="40" alt="PHP" /><br>
        <b>Backend</b>
    </td>
    <td>
        <b>PHP 8.2+ & Laravel 12</b><br>
        Utilização de Eloquent ORM para abstração de banco de dados, Migrations para versionamento de schema e Blade para renderização.
    </td>
  </tr>
  <tr>
    <td align="center" width="100">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/postgresql/postgresql-original.svg" width="40" height="40" alt="PostgreSQL" /><br>
        <b>Database</b>
    </td>
    <td>
        <b>PostgreSQL</b><br>
        Banco de dados relacional robusto, utilizado para garantir a integridade das chaves estrangeiras e transações ACID.
    </td>
  </tr>
  <tr>
    <td align="center" width="100">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg" width="40" height="40" alt="Tailwind" /><br>
        <b>Frontend</b>
    </td>
    <td>
        <b>Tailwind CSS & Alpine.js</b><br>
        Design system utility-first para uma interface limpa, responsiva e minimalista, com interatividade leve via Alpine.
    </td>
  </tr>
  <tr>
    <td align="center" width="100">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/npm/npm-original-wordmark.svg" width="40" height="40" alt="Tools" /><br>
        <b>Tools</b>
    </td>
    <td>
        <b>Vite, Chart.js & SweetAlert2</b><br>
        Compilação de assets otimizada, visualização de dados e interface de alertas moderna.
    </td>
  </tr>
</table>

---

<h2 id="instalacao">Instalação e Configuração</h2>

Pré-requisitos: PHP 8.2+, Composer, Node.js e PostgreSQL.

```bash
# 1. Clone o repositório
git clone [https://github.com/SEU-USUARIO/controle-financeiro.git](https://github.com/SEU-USUARIO/controle-financeiro.git)
cd controle-financeiro

# 2. Instale as dependências
composer install
npm install

# 3. Configure o ambiente
cp .env.example .env
# (Edite o arquivo .env com as credenciais do seu banco de dados PostgreSQL)

# 4. Gere a chave da aplicação e migre o banco
php artisan key:generate
php artisan migrate

# 5. Inicie o servidor local
npm run dev
php artisan serve
