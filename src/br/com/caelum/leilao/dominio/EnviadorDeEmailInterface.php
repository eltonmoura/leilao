<?php
namespace src\br\com\caelum\leilao\dominio;

interface EnviadorDeEmailInterface {
    public function envia(Leilao $leilao);
}
