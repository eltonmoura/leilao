<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\factory\ConnectionFactory;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\dao\UsuarioDao;
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
        $dono = new Usuario('Elton', 'elton@corp.com');
        $usuario1 = new Usuario('Mario', 'mario@corp.com');
        $usuario2 = new Usuario('Pedro', 'pedro@corp.com');
        
        $usuarioDao = new UsuarioDao($this->con);
        
        $usuarioDao->salvar($dono);
        $usuarioDao->salvar($usuario1);
        $usuarioDao->salvar($usuario2);
 
        $leilao = $this->leilaoBuilder
            ->comDescricao('Outro Leilão')
            ->comDono($dono)
            ->comLance(100, $usuario1)
            ->comLance(150, $usuario2)
            ->naData(new \DateTime('2017-11-01'))
            ->cria();

        $deuCerto = $this->dao->salvar($leilao);
        
        $this->assertTrue($deuCerto);
        
        #$leilaoDoBanco = $this->dao->porPeriodo(new \DateTime('2017-10-29'), new \DateTime('2017-11-02'));
        
        #$this->assertCount(1, $leilaoDoBanco);
        #$this->assertEquals($leilao->getDescricao(), $leilaoDoBanco->getDescricao());
    }
}
