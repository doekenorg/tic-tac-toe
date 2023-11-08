<?php

namespace DoekeNorg\TicTacToe;

final class Grid
{
    private \SplFixedArray $squares;

    private function __construct(public readonly int $size)
    {
        if ($this->size < 1) {
            throw new \InvalidArgumentException('Grid needs at least one square.');
        }

        $this->squares = new \SplFixedArray($size * $size);
    }

    public static function empty(int $size = 3): Grid
    {
        return new self($size);
    }

    public function placeMark(int $i, Mark $mark): Grid
    {
        $grid = clone $this;

        if (isset($grid->squares[$i])) {
            throw new \RuntimeException('Square already has a mark.');
        }

        $grid->squares[$i] = $mark;

        return $grid;
    }

    /**
     * @return \SplFixedArray|Mark[]
     */
    public function getSquares(): \SplFixedArray
    {
        return clone $this->squares;
    }
}
