<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\PessoaDAO;

try {

    $dao = new PessoaDAO();

    $pessoas = $dao->listar();

} catch (\PDOException $e) {

    die("Erro ao listar pessoas: " . $e->getMessage());

}

$content = '

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Lista de Pessoas</h2>

        <div>

            <a href="pessoa-pesquisar.php" class="btn btn-info">
                Pesquisar
            </a>

            <a href="pessoa-form.php" class="btn btn-primary">
                Cadastrar Pessoa
            </a>

        </div>

    </div>

    <table class="table table-striped table-bordered">

        <thead class="table-dark">

            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>CPF</th>
                <th>Endereço</th>
                <th>Criado em</th>
                <th>Atualizado em</th>
                <th>Ações</th>
            </tr>

        </thead>

        <tbody>
';

foreach ($pessoas as $pessoa) {

    $id = htmlspecialchars($pessoa['id']);
    $nome = htmlspecialchars($pessoa['nome']);
    $telefone = htmlspecialchars($pessoa['telefone'] ?? '');
    $cpf = htmlspecialchars($pessoa['cpf']);
    $endereco = htmlspecialchars($pessoa['endereco'] ?? '');
    $createdAt = htmlspecialchars($pessoa['createdAt'] ?? '');
    $updatedAt = htmlspecialchars($pessoa['updatedAt'] ?? '');

    $content .= '

            <tr>

                <td>' . $id . '</td>

                <td>' . $nome . '</td>

                <td>' . $telefone . '</td>

                <td>' . $cpf . '</td>

                <td>' . $endereco . '</td>

                <td>' . $createdAt . '</td>

                <td>' . $updatedAt . '</td>

                <td>

                   <a href="pessoa-alterar.php?id=' . $id . '"
   class="btn btn-warning btn-sm">
    Alterar
</a>

<a href="pessoa-excluir.php?id=' . $id . '"
   class="btn btn-danger btn-sm"
   onclick="return confirm(\'Tem certeza que deseja excluir esta pessoa?\')">
    Excluir
</a>
                </td>

            </tr>

    ';
}

$content .= '

        </tbody>

    </table>

</div>

';

include "layout.php";
?>