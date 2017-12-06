<?php
namespace src\br\com\caelum\leilao\dominio;

class Usuario
{
    private $id;
    private $nome;
    private $email;

    public function __construct($nome = null, $email = null)
    {
        $this->nome = $nome;
        $this->email = $email;
    }

    public function getNome()
    {
        return $this->nome;
    }
    
    public function getEmail()
    {
        return $this->nome;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
}
