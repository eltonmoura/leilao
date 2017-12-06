<?php
namespace src\br\com\caelum\leilao\dominio;

class EncerradorDeLeiloes
{
    private $dao;
    private $total = 0;
    private $carteiro;

    public function __construct(LeilaoCrudDaoInterface $dao, EnviadorDeEmailInterface $carteiro)
    {   
        $this->dao = $dao;
        $this->carteiro = $carteiro;
    }
    
    public function encerrar()
    {
        $leiloesCorrentes = $this->dao->correntes();
        
        foreach ($leiloesCorrentes as $leilao) {
            if ($this->comecouSemanaPassada($leilao)) {
                $leilao->encerra();
                $this->total++;
                $this->dao->atualiza($leilao);
                $this->carteiro->envia($leilao);
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
