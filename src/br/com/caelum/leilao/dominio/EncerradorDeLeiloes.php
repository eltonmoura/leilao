<?php
namespace src\br\com\caelum\leilao\dominio;

use tests\br\com\caelum\leilao\LeilaoCrudDao;

class EncerradorDeLeiloes
{
    private $dao;
    private $total = 0;

    public function __construct(LeilaoCrudDao $dao)
    {   
        $this->dao = $dao;
    }
    
    public function encerrar()
    {
        $leiloesCorrentes = $this->dao->correntes();
        
        foreach ($leiloesCorrentes as $leilao) {
            if ($this->comecouSemanaPassada($leilao)) {
                $leilao->encerra();
                $this->total++;
                $this->dao->atualiza($leilao);
            }
        }
    }

    public function comecouSemanaPassada(Leilao $leilao)
    {
        $dataSemanaPassada = (new \DateTime())->sub(new \DateInterval('P7D'));
        return ($leilao->getData() < $dataSemanaPassada);
    }
    
    public function getTotal()
    {
        return $this->total;
    }
}
