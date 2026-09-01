<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Conexao;

$pdo = Conexao::conectar();

echo "Conectado ao banco aula6 com sucesso!";