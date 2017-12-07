<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\factory\ConnectionFactory;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\dominio\LeilaoBuilder;

class LeilaoDaoTest extends TestCase
{
    private $dao;
    private $con;
    private $leilaoBuilder;

    /**
     * @before
     */
    public function setUp()
    {
        $this->con = ConnectionFactory::getConnection();
        $this->con->beginTransaction();
        $this->dao = new LeilaoDao($this->con);
        $this->leilaoBuilder = new LeilaoBuilder();
    }

    public function testDeveRetornarLeilaoPorPeriodo()
    {
        $leilao = $this->leilaoBuilder
            ->comDescricao('Outro Leilão')
            ->comDono(new Usuario('Elton', 'elton@corp.com'))
            ->comLance(100, new Usuario('Mario', 'mario@corp.com'))
            ->comLance(150, new Usuario('Pedro', 'pedro@corp.com'))
            ->naData(new \DateTime('2017-11-01'))
            ->cria();

        #$this->dao->salvar($leilao);

        #$leilaoDoBanco = $this->dao->porPeriodo(new \DateTime('2017-10-29'), new \DateTime('2017-11-02'));
        #$this->assertCount(1, $leilaoDoBanco);
        #$this->assertEquals($leilao->getDescricao(), $leilaoDoBanco->getDescricao());
    }
}
