<?php

namespace App\DAO;

use App\Config\Database;
use PDO;

class MovimentacaoDAO
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::conectar();
    }

    public function listar(): array
    {
        $sql = "SELECT
                    m.id,
                    m.idPessoa,
                    m.Credito,
                    m.Debito,
                    m.DataOperacao,
                    m.Observacao,
                    m.CreatedAt,
                    p.nome AS pessoa
                FROM movimentacao m
                INNER JOIN pessoas p ON p.id = m.idPessoa
                ORDER BY m.id DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function pesquisarPessoa(string $nome): array
    {
        $sql = "SELECT id, nome, cpf
                FROM pessoas
                WHERE nome LIKE :nome
                ORDER BY nome ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':nome' => '%' . $nome . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPessoa(int $id): ?array
    {
        $sql = "SELECT id, nome, cpf
                FROM pessoas
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pessoa ?: null;
    }

    public function depositar(int $idPessoa, float $valor): bool
    {
        $pessoa = $this->buscarPessoa($idPessoa);

        if (!$pessoa) {
            die("Pessoa inválida.");
        }

        $sql = "INSERT INTO movimentacao
                (idPessoa, Credito, Debito, Observacao)
                VALUES
                (:idPessoa, :credito, 0, 'Depósito')";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':idPessoa' => $idPessoa,
            ':credito' => $valor
        ]);
    }

    public function sacar(int $idPessoa, float $valor): bool
    {
        $pessoa = $this->buscarPessoa($idPessoa);

        if (!$pessoa) {
            die("Pessoa inválida.");
        }

        $sql = "SELECT
                    COALESCE(SUM(Credito), 0) -
                    COALESCE(SUM(Debito), 0)
                FROM movimentacao
                WHERE idPessoa = :idPessoa";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':idPessoa' => $idPessoa
        ]);

        $saldo = (float) $stmt->fetchColumn();

        if ($saldo < $valor) {
            die("Saldo insuficiente. Saldo atual: R$ " .
                number_format($saldo, 2, ',', '.'));
        }

        $sql = "INSERT INTO movimentacao
                (idPessoa, Credito, Debito, Observacao)
                VALUES
                (:idPessoa, 0, :debito, 'Saque')";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':idPessoa' => $idPessoa,
            ':debito' => $valor
        ]);
    }

    public function transferir(
        int $idOrigem,
        int $idDestino,
        float $valor
    ): bool {
        if ($idOrigem === $idDestino) {
            die("Não é possível transferir para a mesma pessoa.");
        }

        $origem = $this->buscarPessoa($idOrigem);
        $destino = $this->buscarPessoa($idDestino);

        if (!$origem) {
            die("Pessoa de origem inválida.");
        }

        if (!$destino) {
            die("Pessoa de destino inválida.");
        }

        $sql = "SELECT
                    COALESCE(SUM(Credito), 0) -
                    COALESCE(SUM(Debito), 0)
                FROM movimentacao
                WHERE idPessoa = :idPessoa";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':idPessoa' => $idOrigem
        ]);

        $saldo = (float) $stmt->fetchColumn();

        if ($saldo < $valor) {
            die("Saldo insuficiente. Saldo atual: R$ " .
                number_format($saldo, 2, ',', '.'));
        }

        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO movimentacao
                    (idPessoa, Credito, Debito, Observacao)
                    VALUES
                    (:idPessoa, 0, :valor, 'Transferência enviada')";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':idPessoa' => $idOrigem,
                ':valor' => $valor
            ]);

            $sql = "INSERT INTO movimentacao
                    (idPessoa, Credito, Debito, Observacao)
                    VALUES
                    (:idPessoa, :valor, 0, 'Transferência recebida')";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':idPessoa' => $idDestino,
                ':valor' => $valor
            ]);

            $this->conn->commit();

            return true;

        } catch (\PDOException $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            die("ERRO NO BANCO: " . $e->getMessage());
        }
    }
}