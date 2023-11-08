<?php

namespace DoekeNorg\TicTacToe;

final class AsciiGridOutput implements GridOutput
{
    private string $output;

    public function output(Grid $grid): void
    {
        $this->output = '';
        foreach ($grid->getSquares() as $i => $square) {
            if ($i === 0 || $i % $grid->size === 0) {
                $this->output .= ($this->output !== '' ? "\n" : '');
                $this->output .= $this->line($grid->size);
                $this->output .= "\n|";
            }
            $this->output .= sprintf(' %s ', $square?->value ?? ' ') . '|';
        }
        $this->output .= "\n" . $this->line($grid->size);
    }

    private function line(int $size): string
    {
        return '+' . str_repeat('---+', $size);
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}
