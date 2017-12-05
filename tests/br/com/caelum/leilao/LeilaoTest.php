<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\dominio\Leilao;

class LeilaoTest extends TestCase
{
    public function testDeveRetornarUmLance()
    {
        $gordo = new Usuario('Andre');
        $lance = new Lance(100, $gordo);

        $leilao = new Leilao('Tinder Premio', []);
        $leilao->propoe($lance);
        $this->assertEquals(1, count($leilao->getLances()));
    }
    
    public function testDeveRetornarVariosLances()
    {
        $gordo = new Usuario('Andre');
        $se = new Usuario('Seu Zé');
        
        $leilao = new Leilao('Tinder Plus', []);
        
        $leilao->propoe(new Lance(100, $gordo));
        $leilao->propoe(new Lance(50, $se));
        $leilao->propoe(new Lance(250, $gordo));
        
        $this->assertEquals(1, count($leilao->getLances()));
    }
    
    public function testNaoDeveAceitarMaisDe5Lances()
    {
        $gordo = new Usuario('Andre');
        $se = new Usuario('Seu Zé');
        
        $leilao = new Leilao('Tinder Plus', []);
        
        $leilao->propoe(new Lance(50, $gordo));
        $leilao->propoe(new Lance(55, $se));
        $leilao->propoe(new Lance(60, $gordo));
        $leilao->propoe(new Lance(65, $se));
        $leilao->propoe(new Lance(70, $gordo));
        $leilao->propoe(new Lance(75, $se));
        $leilao->propoe(new Lance(80, $gordo));
        $leilao->propoe(new Lance(85, $se));
        $leilao->propoe(new Lance(90, $gordo));
        $leilao->propoe(new Lance(95, $se));
        $leilao->propoe(new Lance(100, $gordo));
        $leilao->propoe(new Lance(105, $se));
       
        $this->assertEquals(10, count($leilao->getLances()));
    }
    
    public function testNaoPodeDarLanceMenor()
    {
        $gordo = new Usuario('Andre');
        $se = new Usuario('Seu Zé');
        
        $leilao = new Leilao('Tinder Plus', []);
        
        $leilao->propoe(new Lance(50, $gordo));
        $leilao->propoe(new Lance(55, $se));
        $leilao->propoe(new Lance(60, $gordo));
        $leilao->propoe(new Lance(10, $se));
        $leilao->propoe(new Lance(15, $se));
        
        $this->assertEquals(3, count($leilao->getLances()));
    }
    
    public function testNaoPodeDarLancesSeguidos()
    {
        $gordo = new Usuario('Andre');
        $se = new Usuario('Seu Zé');
        
        $leilao = new Leilao('Tinder Plus', []);
        
        $leilao->propoe(new Lance(50, $gordo));
        $leilao->propoe(new Lance(55, $se));
        $leilao->propoe(new Lance(60, $gordo));
        $leilao->propoe(new Lance(65, $gordo));
        $leilao->propoe(new Lance(70, $se));
        $leilao->propoe(new Lance(85, $se));
        
        $this->assertEquals(4, count($leilao->getLances()));
    }
}
