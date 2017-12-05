<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\LeilaoBuilder;
use src\br\com\caelum\leilao\dominio\Usuario;

class LeilaoTest extends TestCase
{
    public function setUp()
    {
        $this->gordo = new Usuario('Andre');
        $this->ze = new Usuario('Seu Zé');
        $this->leilaoBuilder = new LeilaoBuilder();
    }

    public function testDeveRetornarUmLance()
    {
        $leilao = $this->leilaoBuilder
            ->comDescricao('Tinder Premio')
            ->comLance(100, $this->gordo)
            ->cria();
        $this->assertEquals(1, count($leilao->getLances()));
    }

    public function testDeveRetornarVariosLances()
    {
        $leilao = $this->leilaoBuilder
            ->comDescricao('Tinder Plus')
            ->comLance(100, $this->gordo)
            ->comLance(150, $this->ze)
            ->comLance(250, $this->gordo)
            ->cria();

        $this->assertEquals(3, count($leilao->getLances()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNaoDeveAceitarMaisDe5Lances()
    {
        $leilao = $this->leilaoBuilder
            ->comDescricao('Tinder Plus')
            ->comLance(50, $this->gordo)
            ->comLance(55, $this->ze)
            ->comLance(60, $this->gordo)
            ->comLance(65, $this->ze)
            ->comLance(70, $this->gordo)
            ->comLance(75, $this->ze)
            ->comLance(80, $this->gordo)
            ->comLance(85, $this->ze)
            ->comLance(90, $this->gordo)
            ->comLance(95, $this->ze)
            ->comLance(100, $this->gordo)
            ->comLance(105, $this->ze)
            ->cria();

        $this->assertEquals(10, count($leilao->getLances()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNaoPodeDarLanceMenor()
    {
        $leilao = $this->leilaoBuilder
            ->comDescricao('Tinder Plus')
            ->comLance(50, $this->gordo)
            ->comLance(55, $this->ze)
            ->comLance(60, $this->gordo)
            ->comLance(10, $this->ze)
            ->comLance(15, $this->ze)
            ->cria();

        $this->assertEquals(3, count($leilao->getLances()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNaoPodeDarLancesSeguidos()
    {
        $leilao = $this->leilaoBuilder
            ->comDescricao('Tinder Plus')
            ->comLance(50, $this->gordo)
            ->comLance(55, $this->ze)
            ->comLance(60, $this->gordo)
            ->comLance(65, $this->gordo)
            ->comLance(70, $this->ze)
            ->comLance(85, $this->ze)
            ->cria();

        $this->assertEquals(4, count($leilao->getLances()));
    }
}
