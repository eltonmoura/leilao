<?php
namespace src\br\com\caelum\leilao\dominio;

class LeilaoBuilder
{
    private $descricao;
    private $lances;
    private $data;
    private $dono;

    public function comLance(float $valor, Usuario $usuario)
    {
        $this->lances[] = new Lance($valor, $usuario);
        return $this;
    }

    public function comDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function comDono(Usuario $dono)
    {
        $this->dono = $dono;
        return $this;
    }

    public function naData(\DateTime $data)
    {
        $this->data = $data;
        return $this;
    }

    public function cria()
    {   
        $leilao = new Leilao();
        $leilao->setDescricao($this->descricao);
        if (isset($this->dono)) {
            $leilao->setDono($this->dono);
        }
        if (isset($this->data)) {
            $leilao->setData($this->data);
        }
        if (isset($this->lances)) {
            $leilao->setLances($this->lances);
        }

        unset($this->descricao);
        unset($this->lances);
        unset($this->data);
        unset($this->dono);

        return $leilao;
    }
}
