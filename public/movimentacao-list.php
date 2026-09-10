<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\MovimentacaoDAO;

$pessoaId = (int) ($_POST['pessoa_id'] ?? 0);
$tipo = $_POST['tipo'] ?? '';
$valor = (float) ($_POST['valor'] ?? 0);

if ($pessoaId <= 0 || $valor <= 0) {
    die("Dados inválidos.");
}

$dao = new MovimentacaoDAO();

if ($tipo === 'DEPOSITO') {

    $resultado = $dao->depositar($pessoaId, $valor);

} elseif ($tipo === 'SAQUE') {

    $resultado = $dao->sacar($pessoaId, $valor);

} else {

    die("Transferência deve ser realizada pela opção Transferir.");
}

if ($resultado) {

    header("Location: movimentacao-list.php");
    exit;
}

die("Erro ao realizar movimentação.");
?>