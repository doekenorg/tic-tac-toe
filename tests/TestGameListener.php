<?php

namespace DoekeNorg\TicTacToe\Tests;

use DoekeNorg\TicTacToe\Game;
use DoekeNorg\TicTacToe\GameEventListener;
use DoekeNorg\TicTacToe\Grid;
use DoekeNorg\TicTacToe\Mark;

final class TestGameListener implements GameEventListener
{
    private Grid $last_grid;
    private bool $is_finished = false;
    private bool $is_draw = false;
    private Mark $winner;

    public function gameStarted(Game $game, Grid $grid): void
    {
        $this->last_grid = $grid;
    }

    public function turnSwitched(Mark $mark): void
    {
    }

    public function markPlaced(Grid $grid): void
    {
        $this->last_grid = $grid;
    }

    public function finishedAsDraw(Grid $grid): void
    {
        $this->last_grid = $grid;
        $this->is_finished = true;
        $this->is_draw = true;
    }

    public function finishedWithWinner(Grid $grid, Mark $winner): void
    {
        $this->last_grid = $grid;
        $this->is_finished = true;
        $this->winner = $winner;
    }

    public function getLastGrid(): Grid
    {
        return $this->last_grid;
    }

    public function isFinished(): bool
    {
        return $this->is_finished;
    }

    public function isDraw(): bool
    {
        return $this->is_draw;
    }

    public function findWinner(): ?Mark
    {
        return $this->winner ?? null;
    }
}
