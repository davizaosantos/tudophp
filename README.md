# Sistema de Controle

Sistema desenvolvido em PHP utilizando o padrão MVC para gerenciamento de pessoas e movimentações financeiras.

## Sobre o projeto

O projeto permite realizar o cadastro, listagem e pesquisa de pessoas, além do gerenciamento de movimentações financeiras.

As movimentações disponíveis são:

- Depósito
- Saque
- Transferência

O sistema utiliza PHP 8.2, MySQL, PDO e Composer.

## Funcionalidades

### Pessoas

- Cadastrar pessoa
- Listar pessoas
- Pesquisar pessoa por nome ou CPF
- Alterar pessoa
- Excluir pessoa

### Movimentações

- Cadastrar movimentação
- Listar movimentações
- Depositar
- Sacar
- Transferir
- Pesquisar pessoas pelo nome para realizar movimentações
- Verificar saldo antes de realizar saques e transferências

## Tecnologias utilizadas

- PHP 8.2
- MySQL
- PDO
- Composer
- HTML5
- Bootstrap 5
- Git
- GitHub

## Estrutura do projeto

```text
aula6/
│
├── public/
│   ├── index.php
│   ├── layout.php
│   ├── pessoa-create.php
│   ├── pessoa-cadastrar.php
│   ├── pessoa-listar.php
│   ├── pessoa-pesquisar.php
│   ├── listar.php
│   ├── movimentacao-create.php
│   ├── movimentacao-cadastrar.php
│   └── movimentacao-list.php
│
├── src/
│   ├── Config/
│   │   └── Database.php
│   │
│   ├── Controller/
│   │
│   ├── DAO/
│   │   ├── PessoaDAO.php
│   │   └── MovimentacaoDAO.php
│   │
│   └── Model/
│       ├── Pessoa.php
│       └── Movimentacao.php
│
├── vendor/
│
├── composer.json
├── pessoas.sql
└── README.md
