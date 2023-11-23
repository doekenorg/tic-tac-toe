<?php

namespace DoekeNorg\TicTacToe;

interface GameEventListener
{
    public function gameStarted(Game $game, Grid $grid): void;

    public function turnSwitched(Mark $mark): void;

    public function markPlaced(Grid $grid): void;

    public function finishedAsDraw(Grid $grid): void;

    public function finishedWithWinner(Grid $grid, Mark $winner): void;
}
