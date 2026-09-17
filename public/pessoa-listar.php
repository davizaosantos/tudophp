<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\PessoaDAO;

$dao = new PessoaDAO();

$pessoas = $dao->listar();

$content = '
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Pessoas</h2>

        <a href="pessoa-create.php" class="btn btn-primary">
            Cadastrar Pessoa
        </a>
    </div>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>CPF</th>
                <th>Endereço</th>
                <th>Criado em</th>
                <th>Atualizado em</th>
            </tr>
        </thead>

        <tbody>
';

if (count($pessoas) === 0) {

    $content .= '
            <tr>
                <td colspan="7" class="text-center">
                    Nenhuma pessoa cadastrada.
                </td>
            </tr>
    ';

} else {

    foreach ($pessoas as $pessoa) {

        $content .= '
            <tr>
                <td>' . $pessoa['id'] . '</td>
                <td>' . htmlspecialchars($pessoa['nome']) . '</td>
                <td>' . htmlspecialchars($pessoa['telefone'] ?? '') . '</td>
                <td>' . htmlspecialchars($pessoa['cpf']) . '</td>
                <td>' . htmlspecialchars($pessoa['endereco'] ?? '') . '</td>
                <td>' . $pessoa['createdAt'] . '</td>
                <td>' . $pessoa['updatedAt'] . '</td>
            </tr>
        ';
    }
}

$content .= '
        </tbody>

    </table>

</div>
';

include "layout.php";
?>