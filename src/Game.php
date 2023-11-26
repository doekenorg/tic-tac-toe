<?php

namespace DoekeNorg\TicTacToe;

final class Game
{
    private Mark $turn = Mark::X;
    private Grid $grid;

    private function __construct(
        private readonly GameEventListener $lister,
        int $size = 3,
    ) {
        $this->grid = Grid::empty($size);

        $this->lister->gameStarted($this, $this->grid);
        $this->lister->turnSwitched($this->turn);
    }

    public static function new(GameEventListener $lister, int $size = 3): Game
    {
        return new self($lister, $size);
    }

    public function placeMark(int $square): void
    {
        if ($this->isFinished()) {
            throw new \RuntimeException('Game is already finished.');
        }

        $this->grid = $this->grid->placeMark($square, $this->turn);

        if ($this->isFinished()) {
            $this->isDraw()
                ? $this->lister->finishedAsDraw($this->grid)
                : $this->lister->finishedWithWinner($this->grid, $this->findWinner());
        } else {
            $this->lister->markPlaced($this->grid);
            $this->switchTurn();
        }
    }

    public function squareCount(): int
    {
        return $this->grid->count();
    }

    private function switchTurn(): void
    {
        $this->turn = $this->turn === Mark::X
            ? Mark::O
            : Mark::X;

        $this->lister->turnSwitched($this->turn);
    }

    private function isFinished(): bool
    {
        if ($this->findWinner()) {
            return true;
        }

        return !in_array(null, $this->grid->getSquares()->toArray(), true);
    }

    private function isDraw(): bool
    {
        return $this->isFinished() && !$this->findWinner();
    }

    private function findWinner(): ?Mark
    {
        $squares = $this->grid->getSquares();
        foreach ($this->getLines() as $line) {
            $streak = array_map(fn(int $i) => $squares[$i]?->value, $line);
            if (in_array(null, $streak, true)) {
                // Has empty space
                continue;
            }

            if (count(array_unique($streak)) > 1) {
                // Has more than 1 type of mark
                continue;
            }

            return Mark::from($streak[0]);
        }

        return null;
    }

    private function getLines(): array
    {
        $lines = [];
        $ltr = [];
        $rtl = [];
        $size = $this->grid->size;

        // Get rows and columns.
        for ($i = 0; $i < $size; $i++) {
            $row = [];
            $column = [];
            for ($y = 0; $y < $size; $y++) {
                $row[] = ($i * $size) + $y;
                $column[] = $i + ($y * $size);
            }
            $lines[] = $row;
            $lines[] = $column;

            // Diagonals
            $ltr[] = $i * ($size + 1);
            $rtl[] = ($i + 1) * ($size - 1);
        }

        $lines[] = $ltr;
        $lines[] = $rtl;

        return $lines;
    }
}
