<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\PessoaDAO;

$pesquisa = trim($_GET['pesquisa'] ?? '');

try {

    $dao = new PessoaDAO();

    if ($pesquisa !== '') {
        $pessoas = $dao->pesquisar($pesquisa);
    } else {
        $pessoas = [];
    }

} catch (\PDOException $e) {

    die("Erro ao pesquisar pessoas: " . $e->getMessage());

}

$content = '

<div class="container mt-4">

    <h2>Pesquisar Pessoa</h2>

    <form method="GET" action="pessoa-pesquisar.php" class="mb-4">

        <div class="input-group">

            <input
                type="text"
                name="pesquisa"
                class="form-control"
                placeholder="Digite nome, CPF ou telefone"
                value="' . htmlspecialchars($pesquisa) . '"
            >

            <button type="submit" class="btn btn-primary">
                Pesquisar
            </button>

            <a href="pessoa-pesquisar.php" class="btn btn-secondary">
                Limpar
            </a>

        </div>

    </form>

';

if ($pesquisa !== '') {

    if (count($pessoas) > 0) {

        $content .= '

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

            $id = htmlspecialchars($pessoa['id']);
            $nome = htmlspecialchars($pessoa['nome']);
            $telefone = htmlspecialchars($pessoa['telefone'] ?? '');
            $cpf = htmlspecialchars($pessoa['cpf']);
            $endereco = htmlspecialchars($pessoa['endereco'] ?? '');

            $content .= '

                <tr>

                    <td>' . $id . '</td>

                    <td>' . $nome . '</td>

                    <td>' . $telefone . '</td>

                    <td>' . $cpf . '</td>

                    <td>' . $endereco . '</td>

                    <td>

                        <a href="pessoa-alterar.php?id=' . $id . '"
                           class="btn btn-warning btn-sm">
                            Alterar
                        </a>

                    </td>

                </tr>

            ';
        }

        $content .= '

            </tbody>

        </table>

        ';

    } else {

        $content .= '

        <div class="alert alert-warning">
            Nenhuma pessoa encontrada.
        </div>

        ';
    }
}

$content .= '

    <a href="listar.php" class="btn btn-secondary">
        Voltar para lista
    </a>

</div>

';

include "layout.php";
?>