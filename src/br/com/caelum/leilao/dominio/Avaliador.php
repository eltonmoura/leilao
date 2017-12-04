<?php
namespace src\br\com\caelum\leilao\dominio;

class Avaliador
{
    private $maiorValor = -INF;
    private $menorValor = INF;
    private $valorMedio;
    
    public function avalia(Leilao $leilao)
    {
        if (empty($leilao->getLances())) {
            $this->menorValor = 0;
            $this->maiorValor = 0;
            $this->valorMedio = 0;
            return true;
        }

        $valores = [];
        $total = 0;
        foreach ($leilao->getLances() as $lance) {
            $valores[] = $lance->getValor();
            
            if ($lance->getValor() < $this->menorValor) {
                $this->menorValor = $lance->getValor();
            }
            if ($lance->getValor() > $this->maiorValor) {
                $this->maiorValor = $lance->getValor();
            }
            $total += $lance->getValor();
        }

        rsort($valores);
        $this->tresMaiores = array_chunk($valores, 3)[0];

        $this->valorMedio = ($total / count($leilao->getLances()));
    }

    public function getValorMedio()
    {
        return $this->valorMedio;
    }
    
    public function getTresMaiores()
    {
        return $this->tresMaiores;
    }

    public function getMaiorValor()
    {
        return $this->maiorValor;
    }

    public function getMenorValor()
    {
        return $this->menorValor;
    }   
}
