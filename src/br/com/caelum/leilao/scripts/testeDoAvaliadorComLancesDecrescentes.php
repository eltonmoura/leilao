<?php
namespace src\br\com\caelum\leilao\scripts;

require_once __DIR__ . '/../../../../../../bootstrap.php';

use src\br\com\caelum\leilao\dominio\Avaliador;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

$joao = new Usuario('Joao');
$pedro = new Usuario('Pedro');

$lances = [
    new Lance(700.0, $joao),
    new Lance(600.0, $pedro),
    new Lance(500.0, $joao),
    new Lance(400.0, $pedro),
    new Lance(300.0, $joao),
];

$leilao = new Leilao('Mac da RF', $lances);

$avaliador = new Avaliador();
$avaliador->avalia($leilao);

echo 'MenorValor: ' . $avaliador->getMenorValor() . PHP_EOL;
echo 'MaiorValor: ' . $avaliador->getMaiorValor() . PHP_EOL;
