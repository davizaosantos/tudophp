<?php

require_once "../vendor/autoload.php";

use App\Config\Database;

$pesquisa = $_GET["pesquisa"] ?? "";

try {

    $conn = Database::conectar();

    $sql = "SELECT * FROM pessoas
            WHERE nome LIKE :pesquisa
            OR cpf LIKE :pesquisa
            ORDER BY id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":pesquisa", "%" . $pesquisa . "%");
    $stmt->execute();

    $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao pesquisar: " . $e->getMessage());
}

$content = '
<div class="container mt-4">

    <h2>Pesquisar Pessoa</h2>

    <form method="GET" action="pessoa-pesquisar.php" class="mb-4">

        <div class="input-group">
            <input type="text"
                   name="pesquisa"
                   class="form-control"
                   placeholder="Digite o nome ou CPF"
                   value="' . htmlspecialchars($pesquisa) . '">

            <button type="submit" class="btn btn-primary">
                Pesquisar
            </button>

            <a href="pessoa-pesquisar.php" class="btn btn-secondary">
                Limpar
            </a>
        </div>

    </form>

    <table class="table table-striped table-bordered">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>CPF</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
';

foreach ($pessoas as $pessoa) {

    $content .= '
            <tr>
                <td>' . $pessoa["id"] . '</td>
                <td>' . htmlspecialchars($pessoa["nome"]) . '</td>
                <td>' . htmlspecialchars($pessoa["telefone"] ?? "") . '</td>
                <td>' . htmlspecialchars($pessoa["cpf"]) . '</td>
                <td>' . htmlspecialchars($pessoa["endereco"] ?? "") . '</td>

                <td>
                    <a href="pessoa-alterar.php?id=' . $pessoa["id"] . '"
                       class="btn btn-warning btn-sm">
                        Alterar
                    </a>
                </td>
            </tr>
    ';
}

if (count($pessoas) == 0) {
    $content .= '
            <tr>
                <td colspan="6" class="text-center">
                    Nenhuma pessoa encontrada.
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