<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\DAO\MovimentacaoDAO;

$dao = new MovimentacaoDAO();

$movimentacoes = $dao->listar();

$content = '
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Movimentações</h2>

        <a href="movimentacao-create.php"
           class="btn btn-primary">
            Nova Movimentação
        </a>

    </div>

    <div class="mb-4">

        <a href="movimentacao-create.php?tipo=DEPOSITO"
           class="btn btn-success">
            Depositar
        </a>

        <a href="movimentacao-create.php?tipo=SAQUE"
           class="btn btn-danger">
            Sacar
        </a>

        <a href="movimentacao-create.php?tipo=TRANSFERENCIA"
           class="btn btn-primary">
            Transferir
        </a>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Pessoa</th>
                    <th>Crédito</th>
                    <th>Débito</th>
                    <th>Data</th>
                    <th>Observação</th>
                </tr>

            </thead>

            <tbody>
';

if (count($movimentacoes) === 0) {

    $content .= '
                <tr>
                    <td colspan="6"
                        class="text-center">
                        Nenhuma movimentação cadastrada.
                    </td>
                </tr>
    ';

} else {

    foreach ($movimentacoes as $movimentacao) {

        $credito = (float) $movimentacao['Credito'];
        $debito = (float) $movimentacao['Debito'];

        $content .= '
                <tr>

                    <td>' . $movimentacao['id'] . '</td>

                    <td>' .
                        htmlspecialchars($movimentacao['pessoa']) .
                    '</td>

                    <td>
                        R$ ' .
                        number_format($credito, 2, ',', '.') .
                    '</td>

                    <td>
                        R$ ' .
                        number_format($debito, 2, ',', '.') .
                    '</td>

                    <td>' .
                        $movimentacao['DataOperacao'] .
                    '</td>

                    <td>' .
                        htmlspecialchars($movimentacao['Observacao'] ?? '') .
                    '</td>

                </tr>
        ';
    }
}

$content .= '
            </tbody>

        </table>

    </div>

</div>
';

include "layout.php";
?>