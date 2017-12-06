<?php
namespace tests\br\com\caelum\leilao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\factory\ConnectionFactory;
use src\br\com\caelum\leilao\dao\UsuarioDao;
use src\br\com\caelum\leilao\dominio\Usuario;

class UsuaoDaoTest extends TestCase
{
    private $dao;
    private $con;

    /**
     * @before
     */
    public function setUp()
    {
        $this->con = ConnectionFactory::getConnection();
        $this->con->beginTransaction();
        $this->dao = new UsuarioDao($this->con);
    }
    
    public function testDeveRetornarUsuarioPorNomeEEmail()
    {
        $usuario = new Usuario('Zé', 'ze@bol.com.br');
        $this->dao->salvar($usuario);
        
        $usuarioDoBanco = $this->dao->porNomeEEmail($usuario->getNome(), $usuario->getEmail());        
        
        $this->assertEquals($usuario->getNome(), $usuarioDoBanco->getNome());
    }
}
