<?php

namespace App\DAO;

use App\Config\Database;
use App\Model\Pessoa;
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
        $sql = "SELECT id, nome, cpf
                FROM pessoas
                ORDER BY nome ASC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}