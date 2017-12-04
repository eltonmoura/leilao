<?php
namespace src\br\com\caelum\leilao\dominio;

require_once 'vendor/autoload.php';

$lances = [];

$leilao = new Leilao('Mac da RF', $lances);

$avaliador = new Avaliador();
$avaliador->avalia($leilao);

echo 'MenorValor: ' . $avaliador->getMenorValor() . PHP_EOL;
echo 'MaiorValor: ' . $avaliador->getMaiorValor() . PHP_EOL;
