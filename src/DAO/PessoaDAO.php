<?php

namespace App\DAO;

use App\Model\Pessoa;
use App\Config\Database;
use PDO;

class PessoaDAO
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::conectar();
    }

    public function insert(Pessoa $pessoa): bool
    {
        $sql = "INSERT INTO pessoas
                (nome, telefone, cpf, endereco)
                VALUES
                (:nome, :telefone, :cpf, :endereco)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome' => $pessoa->getNome(),
            ':telefone' => $pessoa->getTelefone(),
            ':cpf' => $pessoa->getCpf(),
            ':endereco' => $pessoa->getEndereco()
        ]);
    }

    public function listar(): array
    {
        $sql = "SELECT * FROM pessoas ORDER BY id DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function pesquisar(string $pesquisa): array
    {
        $sql = "SELECT * FROM pessoas
                WHERE nome LIKE :pesquisa
                OR cpf LIKE :pesquisa
                OR telefone LIKE :pesquisa
                ORDER BY nome ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':pesquisa' => '%' . $pesquisa . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM pessoas WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pessoa ?: null;
    }

    public function alterar(Pessoa $pessoa): bool
    {
        $sql = "UPDATE pessoas SET
                    nome = :nome,
                    telefone = :telefone,
                    cpf = :cpf,
                    endereco = :endereco
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome' => $pessoa->getNome(),
            ':telefone' => $pessoa->getTelefone(),
            ':cpf' => $pessoa->getCpf(),
            ':endereco' => $pessoa->getEndereco(),
            ':id' => $pessoa->getId()
        ]);
    }

    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM pessoas WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}