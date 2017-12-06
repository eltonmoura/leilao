<?php
namespace src\br\com\caelum\leilao\dominio\interfaces;

use src\br\com\caelum\leilao\dominio\Leilao;

interface LeilaoCrudDaoInterface {
    public function correntes();
    public function atualiza(Leilao $leilao);
}
