<?php
namespace src\br\com\caelum\leilao\dominio;

use src\br\com\caelum\leilao\dominio\interfaces\RepositorioDePagamentosInterface;
use src\br\com\caelum\leilao\dominio\interfaces\RepositorioDeLeiloesInterface;

class GeradorDePagamento
{
    private $pagamentos;
    private $leiloes;
    private $avaliador;

    public function __construct(
        RepositorioDeLeiloesInterface $leiloes,
        RepositorioDePagamentosInterface $pagamentos,
        Avaliador $avaliador
    ) {
        $this->pagamentos = $pagamentos;
        $this->leiloes = $leiloes;
        $this->avaliador = $avaliador;
    }

    public function gera()
    {
        $leiloesEncerrados = $this->leiloes->leiloesEncerrados();
        $novosPagamentos = [];

        foreach ($leiloesEncerrados as $leilao) {
            $this->avaliador->avalia($leilao);
            $novoPagamento = new Pagamento($this->avaliador->getMaiorValor(), new \DateTime());
            $novosPagamentos[] = $novoPagamento;
        }
        $this->pagamentos->salvaTodos($novosPagamentos);
        return $novosPagamentos;
    }

    public function getPagamentos()
    {
        return $this->pagamentos;
    }
}
