<?php
namespace src\br\com\caelum\leilao\dominio;

class LeilaoBuilder
{
    private $leilao;
    
    public function __construct()
    {
        $this->leilao = new Leilao();
    }
    
    public function comLance(float $valor, Usuario $usuario)
    {
        $this->leilao->propoe(new Lance($valor, $usuario));
        return $this;
    }
    
    public function comDescricao($descricao)
    {
        $this->leilao->setDescricao($descricao);
        return $this;
    }
    
    public function cria()
    {
        return $this->leilao;
    }
}
