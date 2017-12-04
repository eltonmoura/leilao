<?php
namespace src\br\com\caelum\leilao\scripts;

require_once __DIR__ . '/../../../../../../bootstrap.php';

use src\br\com\caelum\leilao\dominio\Avaliador;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

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
