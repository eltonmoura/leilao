<?php
namespace src\br\com\caelum\leilao\dominio;

require_once 'vendor/autoload.php';

$maria = new Usuario('Maria');
$joao = new Usuario('Joao');

$lances = [
	new Lance(150.0, $maria),
	new Lance(250.0, $joao),
	new Lance(350.0, $joao),
]; 

$leilao = new Leilao('Mac da RF', $lances);

$avaliador = new Avaliador();
$avaliador->avalia($leilao);

echo 'MenorValor: ' . $avaliador->getMenorValor() . PHP_EOL;
echo 'MaiorValor: ' . $avaliador->getMaiorValor() . PHP_EOL;
