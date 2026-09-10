<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\PessoaDAO;

$dao = new PessoaDAO();

$pessoas = $dao->listar();

$content = '
<div class="container mt-4">

    <h2>Cadastrar Movimentação</h2>

    <form action="movimentacao-cadastrar.php" method="POST">

        <div class="mb-3">
            <label for="pessoa_id" class="form-label">Pessoa</label>

            <select name="pessoa_id" id="pessoa_id" class="form-control" required>

                <option value="">Selecione uma pessoa</option>
';

foreach ($pessoas as $pessoa) {

    $content .= '
                <option value="' . $pessoa['id'] . '">
                    ' . htmlspecialchars($pessoa['nome']) . '
                </option>
    ';
}

$content .= '

            </select>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>

            <select name="tipo" id="tipo" class="form-control" required>

                <option value="">Selecione</option>
                <option value="DEPOSITO">Depósito</option>
                <option value="SAQUE">Saque</option>
                <option value="TRANSFERENCIA">Transferência</option>

            </select>
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>

            <input
                type="number"
                name="valor"
                id="valor"
                class="form-control"
                step="0.01"
                min="0.01"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Cadastrar
        </button>

        <a href="movimentacao-listar.php" class="btn btn-secondary">
            Voltar
        </a>

    </form>

</div>
';

include "layout.php";
?>