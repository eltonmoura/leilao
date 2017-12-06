<?php
namespace src\br\com\caelum\leilao\dominio\interfaces;

use src\br\com\caelum\leilao\dominio\Leilao;

interface EnviadorDeEmailInterface {
    public function envia(Leilao $leilao);
}
