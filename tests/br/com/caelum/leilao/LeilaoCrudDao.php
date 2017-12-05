<?php
namespace tests\br\com\caelum\leilao;

use src\br\com\caelum\leilao\dominio\Leilao;

interface LeilaoCrudDao {
    public function correntes();
    public function atualiza(Leilao $leilao);
}
