<?php
namespace src\br\com\caelum\leilao\dominio;

class Leilao
{
    const QTD_LANCES_POR_USUARIO = 5;

    private $descricao;
    private $lances;
    private $qtdLances;
    private $maiorLance = -INF;
    private $data;
    private $encerrado;
    private $dono;
    private $id;
    private $usado;
    private $isEncerrado;

    public function __construct($descricao = '', array $lances = [])
    {
        $this->descricao = $descricao;
        $this->lances = $lances;
        $this->encerrado = false;
    }

    public function propoe(Lance $lance)
    {
        if ($this->getQtdPorUsuario($lance->getUsuario()) >= self::QTD_LANCES_POR_USUARIO) {
            throw new \RuntimeException();
        }

        $ultimoLance = end($this->lances);
        if ($ultimoLance && $ultimoLance->getUsuario() == $lance->getUsuario()) {
            throw new \RuntimeException();
        }

        if ($lance->getValor() <= $this->maiorLance) {
            throw new \RuntimeException();
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

    public function getData()
    {
        return $this->data;
    }

    public function setData(\DateTime $data)
    {
        $this->data = $data;
    }

    public function getEncerrado()
    {
        return $this->encerrado;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function setLances(array $lances)
    {
        $this->lances = $lances;
    }

    public function setEncerrado(bool $encerrado)
    {
        $this->encerrado = $encerrado;
    }

    public function encerra()
    {
        $this->encerrado = true;
    }

    public function getValorInicial()
    {
        if (isset($this->lances[0])) {
            return $this->lances[0]->getValor();
        }
        return null;
    }

    public function getDono()
    {
        return $this->dono;
    }

    public function setDono(Usuario $dono)
    {
        $this->dono = $dono;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getUsado()
    {
        return $this->usado;
    }

    public function isEncerrado()
    {
        return $this->isEncerrado;
    }
}
