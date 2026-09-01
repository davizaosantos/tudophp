<?php

namespace App\Config;

use PDO;
use PDOException;

class Conexao
{
    public static function conectar(): PDO
    {
        try {
            $host = "localhost";
            $banco = "aula6";
            $usuario = "root";
            $senha = "";

            $pdo = new PDO(
                "mysql:host=$host;dbname=$banco;charset=utf8mb4",
                $usuario,
                $senha
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }
}