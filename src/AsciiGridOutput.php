<?php

namespace DoekeNorg\TicTacToe;

/**
 * Outputs a {@see Grid} object as an ASCII representation.
 */
final class AsciiGridOutput
{
    public function output(Grid $grid): string
    {
        $output = '';
        foreach ($grid->getSquares() as $i => $square) {
            if ($i === 0 || $i % $grid->size === 0) {
                $output .= ($output !== '' ? "\n" : '');
                $output .= $this->line($grid->size);
                $output .= "\n|";
            }

            $value = str_pad($this->format($square) ?? ($i + 1), strlen($grid->size * $grid->size));
            $output .= sprintf(' %s ', $value) . '|';
        }
        return $output . "\n" . $this->line($grid->size);
    }

    // Represents a break line that makes up the grid.
    private function line(int $size): string
    {
        return '+' . str_repeat(sprintf('-%s-+', str_repeat('-', strlen($size * $size))), $size);
    }

    private function colorCode(Mark $mark): int
    {
        return match ($mark) {
            Mark::X => 34,
            Mark::O => 32,
        };
    }

    public function format(?Mark $mark): ?string
    {
        if (!$mark) {
            return null;
        }

        return sprintf("\e[1;%dm%s\e[0m", $this->colorCode($mark), $mark->value);
    }
}
