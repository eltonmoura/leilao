<?php
namespace src\br\com\caelum\leilao\dominio;

use PHPUnit\Framework\TestCase;

/**
 * Avaliador test case.
 */
class AvaliadorTest extends TestCase
{
    public function testAvaliaComLancesCrescentes()
    {
        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $jose = new Usuario('Jose');
        $pedro = new Usuario('Pedro');
        $tiago = new Usuario('Tiago');

        $lances = [
            new Lance(150.0, $maria),
            new Lance(250.0, $joao),
            new Lance(350.0, $jose),
            new Lance(400.0, $pedro),
            new Lance(500.0, $tiago),
        ];

        $leilao = new Leilao('Mac da RF', $lances);
        
        $avaliador = new Avaliador();
        $avaliador->avalia($leilao);
      
        $this->assertEquals(150, $avaliador->getMenorValor());
        $this->assertEquals(500, $avaliador->getMaiorValor());
        $this->assertEquals(330, $avaliador->getValorMedio());
    }

    public function testAvaliaComLancesDecrescentes()
    {
        $joao = new Usuario('Joao');
        $pedro = new Usuario('Pedro');
        
        $lances = [
            new Lance(700.0, $joao),
            new Lance(600.0, $pedro),
            new Lance(500.0, $joao),
            new Lance(400.0, $pedro),
            new Lance(300.0, $joao),
        ];
        
        $leilao = new Leilao('Mac da RF', $lances);
        
        $avaliador = new Avaliador();
        $avaliador->avalia($leilao);
        
        $this->assertEquals(300, $avaliador->getMenorValor());
        $this->assertEquals(700, $avaliador->getMaiorValor());
        $this->assertEquals(500, $avaliador->getValorMedio());
    }
    
    public function testAvaliaComUmLance()
    {
        $joao = new Usuario('Joao');
        
        $lances = [
            new Lance(300.0, $joao),
        ];
        
        $leilao = new Leilao('Mac da RF', $lances);
        
        $avaliador = new Avaliador();
        $avaliador->avalia($leilao);
        
        $this->assertEquals(300, $avaliador->getMenorValor());
        $this->assertEquals(300, $avaliador->getMaiorValor());
        $this->assertEquals(300, $avaliador->getValorMedio());
    }
    
    public function testAvaliaSemLances()
    {
        $lances = [];
        
        $leilao = new Leilao('Mac da RF', $lances);
        
        $avaliador = new Avaliador();
        $avaliador->avalia($leilao);
        
        $this->assertEquals(0, $avaliador->getMenorValor());
        $this->assertEquals(0, $avaliador->getMaiorValor());
        $this->assertEquals(0, $avaliador->getValorMedio());
    }
    
    public function testDeveEncontrarOsTresMaioresLances()
    {
        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $jose = new Usuario('Jose');
        $pedro = new Usuario('Pedro');
        $tiago = new Usuario('Tiago');
        
        $lances = [
            new Lance(150.0, $maria),
            new Lance(250.0, $joao),
            new Lance(350.0, $jose),
            new Lance(400.0, $pedro),
            new Lance(500.0, $tiago),
        ];
        
        $leilao = new Leilao('Mac da RF', $lances);
        
        $avaliador = new Avaliador();
        $avaliador->avalia($leilao);
        
        $this->assertEquals(3, count($avaliador->getTresMaiores()));
        $this->assertEquals([500, 400, 350], $avaliador->getTresMaiores());
    }
}
