<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\PessoaDAO;

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: listar.php");
    exit;
}

try {

    $dao = new PessoaDAO();

    $dao->excluir($id);

    header("Location: listar.php");
    exit;

} catch (\PDOException $e) {

    die("Erro ao excluir pessoa: " . $e->getMessage());

}
?>