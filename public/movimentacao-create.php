<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\MovimentacaoDAO;

$dao = new MovimentacaoDAO();

$tipo = $_GET['tipo'] ?? '';

$pesquisa = trim($_GET['pesquisa'] ?? '');
$pesquisaDestino = trim($_GET['pesquisaDestino'] ?? '');

$idPessoa = (int) ($_GET['idPessoa'] ?? 0);
$idPessoaDestino = (int) ($_GET['idPessoaDestino'] ?? 0);

$pessoaSelecionada = null;
$pessoaDestinoSelecionada = null;

if ($idPessoa > 0) {
    $pessoaSelecionada = $dao->buscarPessoa($idPessoa);
}

if ($idPessoaDestino > 0) {
    $pessoaDestinoSelecionada = $dao->buscarPessoa($idPessoaDestino);
}

$resultados = [];

if ($pesquisa !== '') {
    $resultados = $dao->pesquisarPessoa($pesquisa);
}

$resultadosDestino = [];

if ($pesquisaDestino !== '') {
    $resultadosDestino = $dao->pesquisarPessoa($pesquisaDestino);
}

$content = '
<div class="container">

    <h2 class="mb-4">Nova Movimentação</h2>

    <div class="mb-4">
        <a href="movimentacao-create.php?tipo=DEPOSITO"
           class="btn btn-success">
           Depositar
        </a>

        <a href="movimentacao-create.php?tipo=SAQUE"
           class="btn btn-danger">
           Sacar
        </a>

        <a href="movimentacao-create.php?tipo=TRANSFERENCIA"
           class="btn btn-primary">
           Transferir
        </a>
    </div>
';

if ($tipo === 'TRANSFERENCIA') {

    $content .= '
    <div class="card mb-4">
        <div class="card-body">

            <h5>Pesquisar pessoa de origem</h5>

            <form method="GET" class="d-flex gap-2 mb-3">

                <input type="hidden"
                       name="tipo"
                       value="TRANSFERENCIA">

                <input type="hidden"
                       name="idPessoaDestino"
                       value="' . $idPessoaDestino . '">

                <input type="text"
                       name="pesquisa"
                       class="form-control"
                       placeholder="Digite o nome da pessoa"
                       value="' . htmlspecialchars($pesquisa) . '"
                       required>

                <button class="btn btn-primary">
                    Pesquisar
                </button>

            </form>
';

    foreach ($resultados as $pessoa) {

        $content .= '
            <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center">

                <span>
                    <strong>' . htmlspecialchars($pessoa['nome']) . '</strong>
                    - CPF: ' . htmlspecialchars($pessoa['cpf']) . '
                </span>

                <a href="movimentacao-create.php?tipo=TRANSFERENCIA&idPessoa=' .
                    $pessoa['id'] . '&idPessoaDestino=' . $idPessoaDestino . '"
                   class="btn btn-sm btn-success">
                    Selecionar
                </a>

            </div>
        ';
    }

    if ($pessoaSelecionada) {
        $content .= '
            <div class="alert alert-success">
                Origem:
                <strong>' . htmlspecialchars($pessoaSelecionada['nome']) . '</strong>
            </div>
        ';
    }

    $content .= '

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">

            <h5>Pesquisar pessoa de destino</h5>

            <form method="GET" class="d-flex gap-2 mb-3">

                <input type="hidden"
                       name="tipo"
                       value="TRANSFERENCIA">

                <input type="hidden"
                       name="idPessoa"
                       value="' . $idPessoa . '">

                <input type="text"
                       name="pesquisaDestino"
                       class="form-control"
                       placeholder="Digite o nome da pessoa"
                       value="' . htmlspecialchars($pesquisaDestino) . '"
                       required>

                <button class="btn btn-primary">
                    Pesquisar
                </button>

            </form>
';

    foreach ($resultadosDestino as $pessoa) {

        $content .= '
            <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center">

                <span>
                    <strong>' . htmlspecialchars($pessoa['nome']) . '</strong>
                    - CPF: ' . htmlspecialchars($pessoa['cpf']) . '
                </span>

                <a href="movimentacao-create.php?tipo=TRANSFERENCIA&idPessoa=' .
                    $idPessoa . '&idPessoaDestino=' . $pessoa['id'] . '"
                   class="btn btn-sm btn-success">
                    Selecionar
                </a>

            </div>
        ';
    }

    if ($pessoaDestinoSelecionada) {
        $content .= '
            <div class="alert alert-success">
                Destino:
                <strong>' . htmlspecialchars($pessoaDestinoSelecionada['nome']) . '</strong>
            </div>
        ';
    }

    $content .= '
        </div>
    </div>
    ';
} else {

    $content .= '
    <div class="card mb-4">
        <div class="card-body">

            <h5>Pesquisar pessoa</h5>

            <form method="GET" class="d-flex gap-2">

                <input type="hidden"
                       name="tipo"
                       value="' . htmlspecialchars($tipo) . '">

                <input type="text"
                       name="pesquisa"
                       class="form-control"
                       placeholder="Digite o nome da pessoa"
                       value="' . htmlspecialchars($pesquisa) . '"
                       required>

                <button class="btn btn-primary">
                    Pesquisar
                </button>

            </form>
';

    foreach ($resultados as $pessoa) {

        $content .= '
            <div class="border rounded p-2 mt-2 d-flex justify-content-between align-items-center">

                <span>
                    <strong>' . htmlspecialchars($pessoa['nome']) . '</strong>
                    - CPF: ' . htmlspecialchars($pessoa['cpf']) . '
                </span>

                <a href="movimentacao-create.php?tipo=' .
                    htmlspecialchars($tipo) . '&idPessoa=' . $pessoa['id'] . '"
                   class="btn btn-sm btn-success">
                    Selecionar
                </a>

            </div>
        ';
    }

    if ($pessoaSelecionada) {
        $content .= '
            <div class="alert alert-success mt-3">
                Pessoa selecionada:
                <strong>' . htmlspecialchars($pessoaSelecionada['nome']) . '</strong>
            </div>
        ';
    }

    $content .= '
        </div>
    </div>
    ';
}

if ($tipo !== '') {

    $content .= '
    <form action="movimentacao-cadastrar.php" method="POST">

        <input type="hidden"
               name="idPessoa"
               value="' . $idPessoa . '">

        <input type="hidden"
               name="idPessoaDestino"
               value="' . $idPessoaDestino . '">

        <input type="hidden"
               name="tipo"
               value="' . htmlspecialchars($tipo) . '">

        <div class="mb-3">

            <label class="form-label">
                Tipo de movimentação
            </label>

            <input type="text"
                   class="form-control"
                   value="';

    if ($tipo === 'DEPOSITO') {
        $content .= 'Depósito';
    } elseif ($tipo === 'SAQUE') {
        $content .= 'Saque';
    } else {
        $content .= 'Transferência';
    }

    $content .= '"
                   readonly>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Valor
            </label>

            <input type="number"
                   name="valor"
                   class="form-control"
                   step="0.01"
                   min="0.01"
                   required>

        </div>

        <button type="submit"
                class="btn btn-success">
            Realizar Movimentação
        </button>

        <a href="movimentacao-list.php"
           class="btn btn-secondary">
            Voltar
        </a>

    </form>
    ';
}

$content .= '
</div>
';

include "layout.php";
?>