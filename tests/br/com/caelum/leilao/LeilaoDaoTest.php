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

    public function testSeSalvaLeilao()
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
     }

    public function testDeveContarLeiloesNaoEncerrados()
    {
        $dono = new Usuario('Elton', 'elton@corp.com');
        $usuario1 = new Usuario('Mario', 'mario@corp.com');
        $usuario2 = new Usuario('Pedro', 'pedro@corp.com');
        
        $usuarioDao = new UsuarioDao($this->con);
        
        $usuarioDao->salvar($dono);
        $usuarioDao->salvar($usuario1);
        $usuarioDao->salvar($usuario2);
        
        $leilao1 = $this->leilaoBuilder
            ->comDescricao('Primeiro Leilão')
            ->comDono($dono)
            ->comLance(100, $usuario1)
            ->comLance(150, $usuario2)
            ->naData(new \DateTime('2017-11-01'))
            ->cria();
        
        $leilao2 = $this->leilaoBuilder
            ->comDescricao('Segundo Leilão')
            ->comDono($dono)
            ->comLance(160, $usuario1)
            ->comLance(170, $usuario2)
            ->naData(new \DateTime('2017-11-02'))
            ->cria();
        
        #$leilao1->encerra();
        $leilao2->encerra();

        $this->dao->salvar($leilao1);
        $this->dao->salvar($leilao2);
        
        $totalLeiloes = $this->dao->total();        
        $this->assertEquals(1, $totalLeiloes);        
    }
    
    public function testDeveRetornarLeiloesNovos()
    {
        $dono = new Usuario('Elton', 'elton@corp.com');
        $usuario1 = new Usuario('Mario', 'mario@corp.com');
        $usuario2 = new Usuario('Pedro', 'pedro@corp.com');
        
        $usuarioDao = new UsuarioDao($this->con);
        
        $usuarioDao->salvar($dono);
        $usuarioDao->salvar($usuario1);
        $usuarioDao->salvar($usuario2);
        
        $leilao1 = $this->leilaoBuilder
        ->comDescricao('Primeiro Leilão')
        ->comDono($dono)
        ->comLance(100, $usuario1)
        ->comLance(150, $usuario2)
        ->naData(new \DateTime('2017-11-01'))
        ->cria();
        
        $leilao2 = $this->leilaoBuilder
        ->comDescricao('Segundo Leilão')
        ->comDono($dono)
        ->comLance(160, $usuario1)
        ->comLance(170, $usuario2)
        ->naData(new \DateTime('2017-11-02'))
        ->cria();
        
        $leilao1->setUsado(true);
        $leilao2->setUsado(false);

        $this->dao->salvar($leilao1);
        $this->dao->salvar($leilao2);
        
        $totalLeiloes = $this->dao->novos();
        $this->assertEquals($leilao2->getId(), $totalLeiloes[0]->getId());
    }

    public function testDeveRetornarLeiloesAntigos()
    {
        $dono = new Usuario('Elton', 'elton@corp.com');
        $usuario1 = new Usuario('Mario', 'mario@corp.com');
        $usuario2 = new Usuario('Pedro', 'pedro@corp.com');
        
        $usuarioDao = new UsuarioDao($this->con);
        
        $usuarioDao->salvar($dono);
        $usuarioDao->salvar($usuario1);
        $usuarioDao->salvar($usuario2);
        
        $leilao1 = $this->leilaoBuilder
        ->comDescricao('Primeiro Leilão')
        ->comDono($dono)
        ->comLance(100, $usuario1)
        ->comLance(150, $usuario2)
        ->naData((new \DateTime())->sub(new \DateInterval("P8D")))
        ->cria();
        
        $leilao2 = $this->leilaoBuilder
        ->comDescricao('Segundo Leilão')
        ->comDono($dono)
        ->comLance(160, $usuario1)
        ->comLance(170, $usuario2)
        ->naData((new \DateTime())->sub(new \DateInterval("P6D")))
        ->cria();
        
        $this->dao->salvar($leilao1);
        $this->dao->salvar($leilao2);
        
        $totalLeiloes = $this->dao->antigos();
        $this->assertEquals($leilao1->getId(), $totalLeiloes[0]->getId());
    }

    public function testDeveRetornarLeiloesPorPeriodo()
    {
        $dono = new Usuario('Elton', 'elton@corp.com');
        $usuario1 = new Usuario('Mario', 'mario@corp.com');
        $usuario2 = new Usuario('Pedro', 'pedro@corp.com');
        
        $usuarioDao = new UsuarioDao($this->con);
        
        $usuarioDao->salvar($dono);
        $usuarioDao->salvar($usuario1);
        $usuarioDao->salvar($usuario2);
        
        $leilao1 = $this->leilaoBuilder
        ->comDescricao('Primeiro Leilão')
        ->comDono($dono)
        ->comLance(100, $usuario1)
        ->comLance(150, $usuario2)
        ->naData(new \DateTime('2017-01-01'))
        ->cria();
        
        $leilao2 = $this->leilaoBuilder
        ->comDescricao('Segundo Leilão')
        ->comDono($dono)
        ->comLance(160, $usuario1)
        ->comLance(170, $usuario2)
        ->naData(new \DateTime('2017-04-01'))
        ->cria();
       
        $leilao3 = $this->leilaoBuilder
        ->comDescricao('Terceiro Leilão')
        ->comDono($dono)
        ->comLance(175, $usuario1)
        ->comLance(180, $usuario2)
        ->naData(new \DateTime('2017-05-01'))
        ->cria();
        
        $this->dao->salvar($leilao1);
        $this->dao->salvar($leilao2);
        $this->dao->salvar($leilao3);
        
        $totalLeiloes = $this->dao->porPeriodo(new \DateTime('2017-03-01'), new \DateTime('2017-07-01'));
        $this->assertCount(2, $totalLeiloes);
    }
    
    public function testDeveRetornarLeiloesNaoEncerradosComValorNoIntervalo()
    {
        $dono = new Usuario('Elton', 'elton@corp.com');
        $usuario1 = new Usuario('Mario', 'mario@corp.com');
        $usuario2 = new Usuario('Pedro', 'pedro@corp.com');
        
        $usuarioDao = new UsuarioDao($this->con);
        
        $usuarioDao->salvar($dono);
        $usuarioDao->salvar($usuario1);
        $usuarioDao->salvar($usuario2);
        
        $leilao1 = $this->leilaoBuilder
            ->comDescricao('Primeiro Leilão')
            ->comDono($dono)
            ->comLance(100, $usuario1)
            ->comLance(150, $usuario2)
            ->naData(new \DateTime('2017-01-01'))
            ->cria();
        
        $leilao2 = $this->leilaoBuilder
            ->comDescricao('Segundo Leilão')
            ->comDono($dono)
            ->comLance(160, $usuario1)
            ->comLance(170, $usuario2)
            ->naData(new \DateTime('2017-04-01'))
            ->cria();
        
        $leilao3 = $this->leilaoBuilder
            ->comDescricao('Terceiro Leilão')
            ->comDono($dono)
            ->comLance(175, $usuario1)
            ->comLance(180, $usuario2)
            ->naData(new \DateTime('2017-05-01'))
            ->cria();

        $this->dao->salvar($leilao1);
        $this->dao->salvar($leilao2);
        $this->dao->salvar($leilao3);
        
        $totalLeiloes = $this->dao->disputadosEntre(50, 170);
        $this->assertCount(2, $totalLeiloes);
    }
}
