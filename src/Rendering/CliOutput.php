<?php

namespace DoekeNorg\TicTacToe\Rendering;

use DoekeNorg\TicTacToe\AsciiGridOutput;
use DoekeNorg\TicTacToe\Game;

final class CliOutput
{
    private int $lines = 0;
    private string $output = '';
    private AsciiGridOutput $gridOutput;

    public function __construct(
        private readonly Game $game
    ) {
        $this->gridOutput = new AsciiGridOutput();
    }

    public function run(): void
    {
        system("clear");
        while (!$this->game->isFinished()) {
            $this->game->formatOutput($this->gridOutput);
            $this->recordOutput($this->gridOutput->getOutput());
            $this->recordOutput(sprintf("It's %s's turn!", $this->game->turn()->value));
            $this->flushOutput();

            $retry = true;
            while ($retry) {
                $square = $this->askForSquare();
                if ($square === null) {
                    break;
                }

                try {
                    $this->game->placeMark($square - 1);
                    $retry = false;
                } catch (\Exception $e) {
                    $this->removeLines(1);
                    $retry = true;
                }
            }
        }

        // Game is finished
        $this->game->formatOutput($this->gridOutput);
        $this->recordOutput($this->gridOutput->getOutput());

        if ($this->game->isDraw()) {
            $this->recordOutput("That's a draw!");
        } elseif ($winner = $this->game->findWinner()) {
            $this->recordOutput(sprintf("And the winner is: %s!", $winner->value));
        } else {
            $this->recordOutput("Bye");
        }

        $this->flushOutput();
    }

    private function flushOutput(): void
    {
        $this->cleanWindow();

        $this->lines = substr_count($this->output, PHP_EOL) + 1;
        echo $this->output;
        $this->output = '';
    }

    private function askForSquare(): ?int
    {
        $input = null;
        while (!is_numeric($input) || $input < 1 || $input > 9) {
            $input = readline("Which square [1-9] ?");
        }
        return (int)$input;
    }

    private function cleanWindow(): void
    {
        if (!$this->lines) {
            return;
        }

        $this->removeLines($this->lines);
    }

    private function removeLines(int $lines): void
    {
        if ($lines < 1) {
            return;
        }
        echo chr(27) . "[0G";
        echo chr(27) . sprintf("[%dA", $lines);
    }

    private function recordOutput(string $output): void
    {
        $this->output .= $output . PHP_EOL;
    }
}
