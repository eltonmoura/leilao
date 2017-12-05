<?php
namespace src\br\com\caelum\leilao\dominio;

class Usuario
{
    private $id;
    private $nome;

    public function __construct($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }
}
