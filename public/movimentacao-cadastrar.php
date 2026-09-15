<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\MovimentacaoDAO;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: movimentacao-list.php");
    exit;
}

$idPessoa = (int) ($_POST['idPessoa'] ?? 0);
$idPessoaDestino = (int) ($_POST['idPessoaDestino'] ?? 0);
$tipo = $_POST['tipo'] ?? '';
$valor = (float) ($_POST['valor'] ?? 0);

if ($idPessoa <= 0) {
    die("Pessoa inválida.");
}

if ($valor <= 0) {
    die("Valor inválido.");
}

$dao = new MovimentacaoDAO();

if ($tipo === 'DEPOSITO') {

    $resultado = $dao->depositar(
        $idPessoa,
        $valor
    );

} elseif ($tipo === 'SAQUE') {

    $resultado = $dao->sacar(
        $idPessoa,
        $valor
    );

} elseif ($tipo === 'TRANSFERENCIA') {

    if ($idPessoaDestino <= 0) {
        die("Pessoa de destino inválida.");
    }

    if ($idPessoa === $idPessoaDestino) {
        die("Não é possível transferir para a mesma pessoa.");
    }

    $resultado = $dao->transferir(
        $idPessoa,
        $idPessoaDestino,
        $valor
    );

} else {

    die("Tipo de movimentação inválido.");
}

if ($resultado) {
    header("Location: movimentacao-list.php");
    exit;
}

die("Erro ao realizar movimentação.");
?>