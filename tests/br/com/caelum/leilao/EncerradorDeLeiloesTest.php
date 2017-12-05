<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\EncerradorDeLeiloes;
use src\br\com\caelum\leilao\dominio\LeilaoBuilder;

/**
 * EncerradorDeLeiloes test case.
 */
class EncerradorDeLeiloesTest extends TestCase
{
    public function testDeveEncerrarLeiloesComMaisDeUmaSemana()
    {
        $leilaoBuildor = new LeilaoBuilder();

        $leilao = $leilaoBuildor->comDescricao('Coracao do Jojo')
            ->naData(new \DateTime('1970-01-01'))
            ->cria();

        $dao = $this->createMock(LeilaoCrudDao::class);
        
        $dao->method('correntes')
            ->will($this->returnValue([$leilao]));

        $encerrador = new EncerradorDeLeiloes($dao);
        $encerrador->encerrar();

        $this->assertTrue($leilao->getEncerrado());
        $this->assertEquals(1, $encerrador->getTotal());
    }
    
    public function testDeveEncerrarLeiloesDeUmaSemana()
    {
        $leilaoBuilder = new LeilaoBuilder();
        
        $data = new \DateTime();
        $data->sub(new \DateInterval("P6D"));
        
        $leilao = $leilaoBuilder->comDescricao('Coracao do Jojo')
            ->naData($data)
            ->cria();

        $dao = $this->createMock(LeilaoCrudDao::class);
        
        $dao->method('correntes')
            ->will($this->returnValue([$leilao]));
        
        $encerrador = new EncerradorDeLeiloes($dao);
        $encerrador->encerrar();
        
        $this->assertFalse($leilao->getEncerrado());
        $this->assertEquals(0, $encerrador->getTotal());
    }
}
