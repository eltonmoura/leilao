<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Avaliador;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\dominio\LeilaoBuilder;

/**
 * Avaliador test case.
 */
class AvaliadorTest extends TestCase
{
    private $avaliador;
    private $leilaoBuilder;

    /**
     * @before
     */
    public function antes()
    {
        #echo "Antes\n";
        $this->avaliador = new Avaliador();
        $this->leilaoBuilder = new LeilaoBuilder();
    }

    /**
     * @after
     */
    public function depois()
    {
        #echo "Fim\n";
    }

    /**
     * @afterClass
     */
    public static function depoisDaClasse()
    {
        #echo "FimdaClasse\n";
    }

    public function testAvaliaComLancesCrescentes()
    {
        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $jose = new Usuario('Jose');
        $pedro = new Usuario('Pedro');
        $tiago = new Usuario('Tiago');

        $leilao = $this->leilaoBuilder
            ->comDescricao('Mac da RF')
            ->comLance(150.0, $maria)
            ->comLance(250.0, $joao)
            ->comLance(350.0, $jose)
            ->comLance(400.0, $pedro)
            ->comLance(500.0, $tiago)
            ->cria();

        $this->avaliador->avalia($leilao);

        $this->assertEquals(150, $this->avaliador->getMenorValor());
        $this->assertEquals(500, $this->avaliador->getMaiorValor());
        $this->assertEquals(330, $this->avaliador->getValorMedio());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAvaliaComLancesDecrescentes()
    {
        $joao = new Usuario('Joao');
        $pedro = new Usuario('Pedro');
        
        $leilao = $this->leilaoBuilder
            ->comDescricao('Mac da RF')
            ->comLance(700.0, $joao)
            ->comLance(600.0, $pedro)
            ->comLance(500.0, $joao)
            ->comLance(400.0, $pedro)
            ->comLance(300.0, $joao)
            ->cria();

        $this->avaliador->avalia($leilao);
    }

    public function testAvaliaComUmLance()
    {
        $joao = new Usuario('Joao');
        
        $leilao = $this->leilaoBuilder
            ->comDescricao('Mac da RF')
            ->comLance(300.0, $joao)
            ->cria();
        
        $this->avaliador->avalia($leilao);
        
        $this->assertEquals(300, $this->avaliador->getMenorValor());
        $this->assertEquals(300, $this->avaliador->getMaiorValor());
        $this->assertEquals(300, $this->avaliador->getValorMedio());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testAvaliaSemLances()
    {
        $lances = [];

        $leilao = $this->leilaoBuilder
            ->comDescricao('Mac da RF')
            ->cria();
        
        $this->avaliador->avalia($leilao);
    }

    public function testDeveEncontrarOsTresMaioresLances()
    {
        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $jose = new Usuario('Jose');
        $pedro = new Usuario('Pedro');
        $tiago = new Usuario('Tiago');
        
        $leilao = $this->leilaoBuilder
            ->comDescricao('Mac da RF')
            ->comLance(150.0, $maria)
            ->comLance(250.0, $joao)
            ->comLance(350.0, $jose)
            ->comLance(400.0, $pedro)
            ->comLance(500.0, $tiago)
            ->cria();

        $this->avaliador->avalia($leilao);
        
        $this->assertEquals(3, count($this->avaliador->getTresMaiores()));
        $this->assertEquals([500, 400, 350], $this->avaliador->getTresMaiores());
    }
}
