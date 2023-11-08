<?php

namespace DoekeNorg\TicTacToe;

final class Game
{
    private Mark $turn = Mark::X;

    private function __construct(
        private Grid $grid,
    ) {
    }

    public static function new(?Grid $grid = null): Game
    {
        return new self(
            $grid ?? Grid::empty(),
        );
    }

    public function placeMark(int $square): void
    {
        if ($this->isFinished()) {
            throw new \RuntimeException('Game is already finished.');
        }

        $this->grid = $this->grid->placeMark($square, $this->turn);
        $this->opponentsTurn();
    }

    public function turn(): Mark
    {
        return $this->turn;
    }

    private function opponentsTurn(): void
    {
        $this->turn = $this->turn === Mark::X
            ? Mark::O
            : Mark::X;
    }

    public function isFinished(): bool
    {
        if ($this->findWinner()) {
            return true;
        }

        return !in_array(null, $this->grid->getSquares()->toArray(), true);
    }

    public function isDraw(): bool
    {
        return $this->isFinished() && !$this->findWinner();
    }

    public function findWinner(): ?Mark
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

    public function formatOutput(GridOutput $output): void
    {
        $output->output($this->grid);
    }
}
