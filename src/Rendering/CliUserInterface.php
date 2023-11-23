<?php

namespace DoekeNorg\TicTacToe\Rendering;

use DoekeNorg\TicTacToe\AsciiGridOutput;
use DoekeNorg\TicTacToe\Game;
use DoekeNorg\TicTacToe\Grid;
use DoekeNorg\TicTacToe\Mark;
use DoekeNorg\TicTacToe\GameEventListener;

final class CliUserInterface implements GameEventListener
{
    private bool $is_running = false;
    private int $lines = 0;
    private string $output = '';
    private AsciiGridOutput $gridOutput;
    private Game $game;

    public function __construct()
    {
        $this->gridOutput = new AsciiGridOutput();
    }

    public function markPlaced(Grid $grid): void
    {
        $this->recordGrid($grid);
    }

    public function finishedAsDraw(Grid $grid): void
    {
        $this->is_running = false;
        $this->recordGrid($grid);
        $this->recordOutput('Game ended as a draw.');
    }

    public function finishedWithWinner(Grid $grid, Mark $winner): void
    {
        $this->is_running = false;
        $this->recordGrid($grid);
        $this->recordOutput(sprintf('And the winner is: %s', $winner->value));
    }

    public function gameStarted(Game $game, Grid $grid): void
    {
        $this->game = $game;
        $this->recordGrid($grid);
    }

    public function turnSwitched(Mark $mark): void
    {
        $this->recordTurn($mark);
    }

    public function run(): void
    {
        $this->is_running = true;

        system('clear');
        while ($this->is_running) {
            $this->flushOutput();

            while (true) {
                $square = $this->askForSquare();
                try {
                    $this->game->placeMark($square - 1);
                    break;
                } catch (\Exception) {
                    $this->removeLines(1);
                }
            }
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
        $max_size = $this->game->size() * $this->game->size();
        while (!is_numeric($input) || $input < 1 || $input > $max_size) {
            if ($input !== null) {
                $this->removeLines(1);
            }
            $input = readline(sprintf('Which square [1-%d] ?', $max_size));
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

    private function recordGrid(Grid $grid): void
    {
        $this->cleanWindow();
        $this->recordOutput($this->gridOutput->output($grid));
    }

    private function recordTurn(Mark $turn): void
    {
        $this->recordOutput(sprintf("It's %s's turn!", $turn->value));
    }
}
