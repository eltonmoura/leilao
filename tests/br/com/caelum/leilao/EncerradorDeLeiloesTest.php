<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\EncerradorDeLeiloes;
use src\br\com\caelum\leilao\dominio\interfaces\LeilaoCrudDaoInterface;
use src\br\com\caelum\leilao\dominio\interfaces\EnviadorDeEmailInterface;
use src\br\com\caelum\leilao\dominio\LeilaoBuilder;

/**
 * EncerradorDeLeiloes test case.
 */
class EncerradorDeLeiloesTest extends TestCase
{
    public function testDeveEncerrarLeiloesComMaisDeUmaSemana()
    {
        $leilaoBuildor = new LeilaoBuilder();

        $data = (new \DateTime())->sub(new \DateInterval("P8D"));

        $leilao = $leilaoBuildor->comDescricao('Coracao do Jojo')
            ->naData($data)
            ->cria();

        $dao = $this->createMock(LeilaoCrudDaoInterface::class);
        
        $carterio = $this->createMock(EnviadorDeEmailInterface::class);

        $dao->method('correntes')
            ->will($this->returnValue([$leilao]));

        $dao->expects($this->atLeastOnce())->method('atualiza');
        $carterio->expects($this->atLeastOnce())->method('envia');

        $encerrador = new EncerradorDeLeiloes($dao, $carterio);
        $encerrador->encerrar();

        $this->assertTrue($leilao->getEncerrado());
        $this->assertEquals(1, $encerrador->getTotal());
    }
    
    public function testNaoDeveEncerrarLeiloesDeMenosUmaSemana()
    {
        $leilaoBuilder = new LeilaoBuilder();

        $data = (new \DateTime())->sub(new \DateInterval("P6D"));

        $leilao = $leilaoBuilder->comDescricao('Coracao do Jojo')
            ->naData($data)
            ->cria();

        $dao = $this->createMock(LeilaoCrudDaoInterface::class);

        $carterio = $this->createMock(EnviadorDeEmailInterface::class);

        $dao->method('correntes')
            ->will($this->returnValue([$leilao]));

        $dao->expects($this->never())->method('atualiza');
        $carterio->expects($this->never())->method('envia');

        $encerrador = new EncerradorDeLeiloes($dao, $carterio);
        $encerrador->encerrar();

        $this->assertFalse($leilao->getEncerrado());
        $this->assertEquals(0, $encerrador->getTotal());
    }

    public function testQuandoDaoLancaException()
    {
        $leilaoBuilder = new LeilaoBuilder();

        $data = (new \DateTime())->sub(new \DateInterval("P8D"));

        $leilao = $leilaoBuilder->comDescricao('Cafeteira')
            ->naData($data)
            ->cria();

        $dao = $this->createMock(LeilaoCrudDaoInterface::class);

        $carterio = $this->createMock(EnviadorDeEmailInterface::class);

        $dao->method('correntes')
            ->will($this->returnValue([$leilao]));

        $dao->expects($this->atLeastOnce())->method('atualiza')->will($this->throwException(new \PDOException));

        $carterio->expects($this->never())->method('envia');

        $encerrador = new EncerradorDeLeiloes($dao, $carterio);
        $encerrador->encerrar();

        $this->assertTrue($leilao->getEncerrado());
        $this->assertEquals(0, $encerrador->getTotal());
    }
}
