<?php

namespace DoekeNorg\TicTacToe;

interface GridOutput
{
    public function output(Grid $grid): void;
}
