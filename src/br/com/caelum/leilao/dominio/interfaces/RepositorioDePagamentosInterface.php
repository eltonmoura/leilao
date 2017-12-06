<?php
namespace src\br\com\caelum\leilao\dominio\interfaces;

use src\br\com\caelum\leilao\dominio\Pagamento;

interface RepositorioDePagamentosInterface {

    public function salva(Pagamento $pagamento);

    public function salvaTodos(array $pagamentos);
}
