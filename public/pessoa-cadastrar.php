<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\PessoaDAO;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: pessoa-create.php");
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');

if ($nome === '') {
    die("Nome é obrigatório.");
}

if ($cpf === '') {
    die("CPF é obrigatório.");
}

$telefone = $telefone !== '' ? $telefone : null;
$endereco = $endereco !== '' ? $endereco : null;

$dao = new PessoaDAO();

try {

    $dao->inserir(
        $nome,
        $telefone,
        $cpf,
        $endereco
    );

    header("Location: pessoa-listar.php");
    exit;

} catch (\PDOException $e) {

    if ($e->getCode() === '23000') {
        die("CPF já cadastrado.");
    }

    die("Erro ao cadastrar pessoa: " . $e->getMessage());
}