<?php
namespace src\br\com\caelum\leilao\dominio;

require_once 'vendor/autoload.php';

$joao = new Usuario('Joao');

$lances = [
	new Lance(300.0, $joao),
];

$leilao = new Leilao('Mac da RF', $lances);

$avaliador = new Avaliador();
$avaliador->avalia($leilao);

echo 'MenorValor: ' . $avaliador->getMenorValor() . PHP_EOL;
echo 'MaiorValor: ' . $avaliador->getMaiorValor() . PHP_EOL;
