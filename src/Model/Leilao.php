<?php

namespace Alura\Leilao\Model;

class Leilao {
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao) {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance) {
        if (!empty($this->lances) && $this->ehMesmoUsuario($lance))
            return;
        $this->lances[] = $lance;
    }

    public function recebeLanceMultiplos(Lance $lance) {
        $usuario = $lance->getUsuario();
        $totalLancesUsuario = $this->quantidadeLancesPorUsuario($usuario);
        if ($totalLancesUsuario >= 5)
            return;
        $this->lances[] = $lance;
    }

    private function quantidadeLancesPorUsuario(Usuario $usuario): ?int {
        $totalLancesPorUsuario = 0;

        $totalLancesPorUsuario = array_reduce(
            $this->lances,
            function (?int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() == $usuario) {
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            }
        );

        return $totalLancesPorUsuario;

    }

    /**
     * @return Lance[]
     */
    public function getLances(): array {
        return $this->lances;
    }

    private function ehMesmoUsuario(Lance $lance): bool {
        $ultimoUsuario = $this->lances[count($this->lances) - 1]->getUsuario();
        return $ultimoUsuario == $lance->getUsuario();
    }
}