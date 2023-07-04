<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase {

    public function testLeilaoNaoDeveReceberLancesRepetidos(): void {
        $leilao = new Leilao('PS2');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));

        $lances = $leilao->getLances();
        self::assertCount(1, $lances);
        self::assertEquals(1000, $lances[0]->getValor());
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario(): void {
        $leilao = new Leilao('Brasilia Amarela');
        
        $ana = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao->recebeLanceMultiplos(new Lance($ana, 1000));
        $leilao->recebeLanceMultiplos(new Lance($ana, 2000));
        $leilao->recebeLanceMultiplos(new Lance($ana, 3000));
        $leilao->recebeLanceMultiplos(new Lance($ana, 4000));
        $leilao->recebeLanceMultiplos(new Lance($ana, 4000));

        $leilao->recebeLanceMultiplos(new Lance($maria, 2500));
        $leilao->recebeLanceMultiplos(new Lance($maria, 3500));
        $leilao->recebeLanceMultiplos(new Lance($maria, 4500));
        $leilao->recebeLanceMultiplos(new Lance($maria, 5500));
        $leilao->recebeLanceMultiplos(new Lance($maria, 6500));

        $leilao->recebeLanceMultiplos(new Lance($maria, 7000));

        $lances = $leilao->getLances();
        $posUltimoLance = count($lances) - 1;
        self::assertCount(10, $lances);
        self::assertEquals(6500, $lances[$posUltimoLance]->getValor());
    }

    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(
        int $qtdLances,
        Leilao $leilao,
        array $valores
    ): void {

        $lances = $leilao->getLances();
        self::assertCount($qtdLances, $lances);
        foreach ($valores as $key => $valorEsperado) {
            self::assertEquals($valorEsperado, $lances[$key]->getValor());
        }
    }

    public function geraLances() {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao1 = new Leilao('Uno com escada');
        $leilao1->recebeLance(new Lance($maria, 10000));

        $leilao2 = new Leilao('Fiat 147');
        $leilao2->recebeLance(new Lance($joao, 1000));
        $leilao2->recebeLance(new Lance($maria, 2000));

        return [
            '2-lance' => [1, $leilao1, [10000]],
            '1-lance' => [2, $leilao2, [1000, 2000]],
        ];
    }
}