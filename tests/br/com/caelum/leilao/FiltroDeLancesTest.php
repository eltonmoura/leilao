<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\FiltroDeLances;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Usuario;

class FiltroDeLancesTest extends TestCase
{
    public function testDeveSelecionarLancesEntre1000E3000()
    {
        $joao = new Usuario('João');
        $filtro = new FiltroDeLances();

        $resultado = $filtro->filtra([
            new Lance(1000, $joao),
            new Lance(3000, $joao),
            new Lance(2000, $joao),
            new Lance(800, $joao),
        ]);

        $this->assertEquals(1, count($resultado));
        $this->assertEquals(2000, $resultado[0]->getValor(), 0.00001);
    }

    public function testDeveSelecionarLancesEntre500E3000()
    {
        $joao = new Usuario('João');
        $filtro = new FiltroDeLances();

        $resultado = $filtro->filtra([
            new Lance(600, $joao),
            new Lance(500, $joao),
            new Lance(700, $joao),
            new Lance(800, $joao),
        ]);

        $this->assertEquals(1, count($resultado));
        $this->assertEquals(600, $resultado[0]->getValor(), 0.00001);
    }
}
