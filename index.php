<?php

use DoekeNorg\TicTacToe\Game;
use DoekeNorg\TicTacToe\Rendering\CliOutput;

require 'vendor/autoload.php';

$game = Game::new();
$cli = new CliOutput($game);
$cli->run();
