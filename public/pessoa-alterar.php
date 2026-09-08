<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\PessoaDAO;
use App\Model\Pessoa;

$id = (int) ($_GET['id'] ?? 0);

$dao = new PessoaDAO();

$pessoa = $dao->buscarPorId($id);

if (!$pessoa) {
    die("Pessoa não encontrada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pessoaModel = new Pessoa();

    $pessoaModel->setId($id);
    $pessoaModel->setNome($_POST['nome']);
    $pessoaModel->setTelefone($_POST['telefone'] ?: null);
    $pessoaModel->setCpf($_POST['cpf']);
    $pessoaModel->setEndereco($_POST['endereco'] ?: null);

    if ($dao->alterar($pessoaModel)) {
        header("Location: listar.php");
        exit;
    }

    die("Erro ao alterar pessoa.");
}

$content = '

<div class="container mt-4">

    <h2>Alterar Pessoa</h2>

    <form method="POST">

        <div class="mb-3">

            <label for="nome" class="form-label">
                Nome
            </label>

            <input
                type="text"
                class="form-control"
                id="nome"
                name="nome"
                maxlength="100"
                value="' . htmlspecialchars($pessoa['nome']) . '"
                required
            >

        </div>

        <div class="mb-3">

            <label for="telefone" class="form-label">
                Telefone
            </label>

            <input
                type="text"
                class="form-control"
                id="telefone"
                name="telefone"
                maxlength="15"
                value="' . htmlspecialchars($pessoa['telefone'] ?? '') . '"
            >

        </div>

        <div class="mb-3">

            <label for="cpf" class="form-label">
                CPF
            </label>

            <input
                type="text"
                class="form-control"
                id="cpf"
                name="cpf"
                maxlength="11"
                value="' . htmlspecialchars($pessoa['cpf']) . '"
                required
            >

        </div>

        <div class="mb-3">

            <label for="endereco" class="form-label">
                Endereço
            </label>

            <input
                type="text"
                class="form-control"
                id="endereco"
                name="endereco"
                maxlength="255"
                value="' . htmlspecialchars($pessoa['endereco'] ?? '') . '"
            >

        </div>

        <button type="submit" class="btn btn-warning">
            Alterar
        </button>

        <a href="listar.php" class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>

';

include "layout.php";
?>