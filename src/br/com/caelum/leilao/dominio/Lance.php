<?php
namespace src\br\com\caelum\leilao\dominio;

class Lance
{
    private $valor;
    private $usuario;

    public function __construct(float $valor, Usuario $usuario)
    {
        $this->valor = $valor;
        $this->usuario = $usuario;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }
}
