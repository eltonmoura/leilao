<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\interfaces\RepositorioDeLeiloesInterface;
use src\br\com\caelum\leilao\dominio\interfaces\RepositorioDePagamentosInterface;
use src\br\com\caelum\leilao\dominio\LeilaoBuilder;
use src\br\com\caelum\leilao\dominio\GeradorDePagamento;
use src\br\com\caelum\leilao\dominio\Avaliador;
use src\br\com\caelum\leilao\dominio\Usuario;

class GeradorDePagamentoTest extends TestCase
{
    public function testDeveEmpurrarParaOProximoDiaUtil()
    {
        $leiloes = $this->createMock(RepositorioDeLeiloesInterface::class);
        $pagamentos = $this->createMock(RepositorioDePagamentosInterface::class);

        $leilaoBuilder = new LeilaoBuilder();

        $leilao = $leilaoBuilder
            ->comDescricao('Um Leilao')
            ->comLance(150.0, new Usuario('João'))
            ->naData(new \DateTime())
            ->cria();

        $leiloes->method('leiloesEncerrados')->will($this->returnValue([$leilao]));

        $geradorDePagamento = new GeradorDePagamento($leiloes, $pagamentos, new Avaliador());

        $pagamentos = $geradorDePagamento->gera();
        $pagamentoGerado = $pagamentos[0];
        $this->assertEquals(3, $pagamentoGerado->getData()->format('w'));
    }
}
