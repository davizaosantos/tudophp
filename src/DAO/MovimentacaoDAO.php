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
                    p.nome AS pessoa
                FROM movimentacoes m
                INNER JOIN pessoas p ON p.id = m.idPessoa
                ORDER BY m.id DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function depositar(int $idPessoa, float $valor): bool
    {
        try {

            $sql = "INSERT INTO movimentacoes
                    (idPessoa, Credito, Debito, Observacao)
                    VALUES
                    (:idPessoa, :credito, 0, 'Depósito')";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':idPessoa' => $idPessoa,
                ':credito' => $valor
            ]);

        } catch (\PDOException $e) {

            return false;
        }
    }

    public function sacar(int $idPessoa, float $valor): bool
    {
        try {

            $sql = "SELECT 
                        COALESCE(SUM(Credito), 0) -
                        COALESCE(SUM(Debito), 0)
                    FROM movimentacoes
                    WHERE idPessoa = :idPessoa";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':idPessoa' => $idPessoa
            ]);

            $saldo = (float) $stmt->fetchColumn();

            if ($saldo < $valor) {
                return false;
            }

            $sql = "INSERT INTO movimentacoes
                    (idPessoa, Credito, Debito, Observacao)
                    VALUES
                    (:idPessoa, 0, :debito, 'Saque')";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':idPessoa' => $idPessoa,
                ':debito' => $valor
            ]);

        } catch (\PDOException $e) {

            return false;
        }
    }

    public function transferir(
        int $origem,
        int $destino,
        float $valor
    ): bool {

        if ($origem === $destino) {
            return false;
        }

        try {

            $sql = "SELECT 
                        COALESCE(SUM(Credito), 0) -
                        COALESCE(SUM(Debito), 0)
                    FROM movimentacoes
                    WHERE idPessoa = :idPessoa";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':idPessoa' => $origem
            ]);

            $saldo = (float) $stmt->fetchColumn();

            if ($saldo < $valor) {
                return false;
            }

            $this->conn->beginTransaction();

            $sql = "INSERT INTO movimentacoes
                    (idPessoa, Credito, Debito, Observacao)
                    VALUES
                    (:idPessoa, 0, :valor, 'Transferência enviada')";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':idPessoa' => $origem,
                ':valor' => $valor
            ]);

            $sql = "INSERT INTO movimentacoes
                    (idPessoa, Credito, Debito, Observacao)
                    VALUES
                    (:idPessoa, :valor, 0, 'Transferência recebida')";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':idPessoa' => $destino,
                ':valor' => $valor
            ]);

            $this->conn->commit();

            return true;

        } catch (\PDOException $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return false;
        }
    }
}