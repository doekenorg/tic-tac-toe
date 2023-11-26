<?php

namespace DoekeNorg\TicTacToe;

/**
 * Outputs a {@see Grid} object as an ASCII representation.
 */
final class AsciiGridOutput
{
    public function render(Grid $grid): string
    {
        $output = '';
        $max_length = strlen($grid->count());
        foreach ($grid->getSquares() as $i => $square) {
            if ($i === 0 || $i % $grid->size === 0) {
                $output .= ($output !== '' ? "\n" : '');
                $output .= $this->line($grid->size);
                $output .= "\n|";
            }

            $value = $this->formatMark($square) ?? ($i + 1);

            // Making sure the output does not skew the squares.
            $length = $square ? 1 : strlen($value); // Because of formatting; a square is longer; but effectively 1.
            if ($max_length > 1 && $length < $max_length) {
                $value .= str_repeat(' ', max(0, $max_length - $length));
            }

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

    private function formatMark(?Mark $mark): ?string
    {
        if (!$mark) {
            return null;
        }

        return sprintf("\e[1;%dm%s\e[0m", $this->colorCode($mark), $mark->value);
    }
}
