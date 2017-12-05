<?php
namespace src\br\com\caelum\leilao\dominio;

class Leilao
{
    private $descricao;
    private $lances;
    private $qtdLances;
    private $maiorLance = -INF;
    
    public function __construct($descricao = '', array $lances = [])
    {
        $this->descricao = $descricao;
        $this->lances = $lances;
    }

    public function propoe(Lance $lance)
    {
        if ($this->getQtdPorUsuario($lance->getUsuario()) >= 5) {
            return false;
        }

        $ultimoLance = end($this->lances);
        if ($ultimoLance && $ultimoLance->getUsuario() == $lance->getUsuario()) {
            return false;
        }

        if ($lance->getValor() <= $this->maiorLance) {
            return false;
        }
        $this->maiorLance = $lance->getValor();

        $this->addQtdPorUsuario($lance->getUsuario());
        $this->lances[] = $lance;
    }

    public function getQtdPorUsuario(Usuario $usuario)
    {
        if (!isset($this->qtdLances[$usuario->getNome()])) {
            return 0;
        }
        return $this->qtdLances[$usuario->getNome()];
    }

    public function addQtdPorUsuario(Usuario $usuario)
    {
        if (!isset($this->qtdLances[$usuario->getNome()])) {
            $this->qtdLances[$usuario->getNome()] = 1;
            return true;
        }
        $this->qtdLances[$usuario->getNome()]++;
        return true;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getLances()
    {
        return $this->lances;
    }
    
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
    
    public function setLances(array $lances)
    {
        $this->lances = $lances;
    }
}
