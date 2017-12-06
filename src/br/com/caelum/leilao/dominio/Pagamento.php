<?php
namespace src\br\com\caelum\leilao\dominio;

class Pagamento
{
    private $valor;
    private $data;
    
    public function __construct(float $valor, \DateTime $data)
    {
        $this->valor = $valor;
        $this->data = $data;
    }
    
    public function getValor()
    {
        return $this->valor;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function setValor(float $valor)
    {
        $this->valor = $valor;
    }
    
    public function setData(\DateTime $data)
    {
        $this->data = $data;
    }
}
