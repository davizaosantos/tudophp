<?php

namespace App\DAO;

use App\Config\Database;
use PDO;

class PessoaDAO
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::conectar();
    }

    public function listar(): array
    {
        $sql = "SELECT id, nome, telefone, cpf, endereco, createdAt, updatedAt
                FROM pessoas
                ORDER BY id DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function pesquisar(string $pesquisa): array
    {
        $sql = "SELECT id, nome, telefone, cpf, endereco, createdAt, updatedAt
                FROM pessoas
                WHERE nome LIKE :pesquisa
                   OR cpf LIKE :pesquisa
                ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':pesquisa' => '%' . $pesquisa . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}