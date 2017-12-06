<?php
namespace src\br\com\caelum\leilao\dominio;

use src\br\com\caelum\leilao\dominio\interfaces\LeilaoCrudDaoInterface;
use src\br\com\caelum\leilao\dominio\interfaces\EnviadorDeEmailInterface;

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
            try {
                if ($this->comecouSemanaPassada($leilao)) {
                    $leilao->encerra();
                    $this->dao->atualiza($leilao);
                    $this->total++;
                    $this->carteiro->envia($leilao);
                }
            } catch (\PDOException $e) {
                /// Logar
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
