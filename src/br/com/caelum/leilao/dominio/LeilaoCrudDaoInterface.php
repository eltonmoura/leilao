<?php
namespace src\br\com\caelum\leilao\dominio;

interface LeilaoCrudDaoInterface {
    public function correntes();
    public function atualiza(Leilao $leilao);
}
