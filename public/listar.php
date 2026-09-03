<?php

require_once "../vendor/autoload.php";

use App\Config\Database;

try {
    $conn = Database::conectar();

    $sql = "SELECT * FROM pessoas ORDER BY id DESC";

    $stmt = $conn->query($sql);
    $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao listar pessoas: " . $e->getMessage());
}

$content = '
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Pessoas</h2>

        <a href="pessoa-form.php" class="btn btn-primary">
            Cadastrar Pessoa
        </a>
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

    $content .= '
            <tr>
                <td>' . $pessoa['id'] . '</td>
                <td>' . $pessoa['nome'] . '</td>
                <td>' . $pessoa['telefone'] . '</td>
                <td>' . $pessoa['cpf'] . '</td>
                <td>' . $pessoa['endereco'] . '</td>
                <td>' . $pessoa['createdAt'] . '</td>
                <td>' . $pessoa['updatedAt'] . '</td>

                <td>
                    <a href="pessoa-alterar.php?id=' . $pessoa['id'] . '" 
                       class="btn btn-warning btn-sm">
                        Alterar
                    </a>

                    <a href="pessoa-form.php" 
                       class="btn btn-secondary btn-sm">
                        Limpar
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