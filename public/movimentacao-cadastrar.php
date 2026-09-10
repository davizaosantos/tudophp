<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\MovimentacaoDAO;

$pessoaId = (int) ($_POST['idPessoa'] ?? 0);
$tipo = $_POST['tipo'] ?? '';
$valor = (float) ($_POST['valor'] ?? 0);

if ($pessoaId <= 0) {
    die("Pessoa inválida.");
}

if ($valor <= 0) {
    die("Valor inválido.");
}

$dao = new MovimentacaoDAO();

if ($tipo === 'DEPOSITO') {

    $resultado = $dao->depositar($pessoaId, $valor);

} elseif ($tipo === 'SAQUE') {

    $resultado = $dao->sacar($pessoaId, $valor);

} else {

    die("Tipo de movimentação inválido.");

}

if ($resultado) {
    header("Location: movimentacao-list.php");
    exit;
}

die("Não foi possível realizar a movimentação.");