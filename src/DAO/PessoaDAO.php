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

    public function inserir(
        string $nome,
        ?string $telefone,
        string $cpf,
        ?string $endereco
    ): bool {
        $sql = "INSERT INTO pessoas
                (nome, telefone, cpf, endereco)
                VALUES
                (:nome, :telefone, :cpf, :endereco)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome' => $nome,
            ':telefone' => $telefone,
            ':cpf' => $cpf,
            ':endereco' => $endereco
        ]);
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

    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM pessoas
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}