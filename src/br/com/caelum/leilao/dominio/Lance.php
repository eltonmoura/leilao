<?php
namespace src\br\com\caelum\leilao\dominio;

class Lance
{
    private $valor;
    private $usuario;
    private $data;
    private $leilao;

    public function __construct(float $valor, Usuario $usuario)
    {
        $this->valor = $valor;
        $this->usuario = $usuario;
        $this->data = new \DateTime();
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getLeilao()
    {
        return $this->leilao;
    }
    
    public function setLeilao(Leilao $leilao)
    {
        $this->leilao = $leilao;
    }
}
