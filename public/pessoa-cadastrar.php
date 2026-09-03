<?php

require_once "../vendor/autoload.php";

use App\Config\Conexao;

$nome = $_POST["nome"];
$telefone = $_POST["telefone"];
$cpf = $_POST["cpf"];
$endereco = $_POST["endereco"];

$sql = "INSERT INTO pessoas (nome, telefone, cpf, endereco)
        VALUES (:nome, :telefone, :cpf, :endereco)";

try {
    $conn = Conexao::conectar();

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(":nome", $nome);
    $stmt->bindValue(":telefone", $telefone);
    $stmt->bindValue(":cpf", $cpf);
    $stmt->bindValue(":endereco", $endereco);

    $stmt->execute();

    header("Location: pessoa-listar.php");
    exit;

} catch (PDOException $e) {
    echo "Erro ao cadastrar pessoa: " . $e->getMessage();
}