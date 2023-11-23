<?php

use DoekeNorg\TicTacToe\Game;
use DoekeNorg\TicTacToe\Rendering\CliUserInterface;

require 'vendor/autoload.php';

$size = (int)$argv[1] ?: 3;

$cli = new CliUserInterface();
$game = Game::new($cli, $size);

$cli->run();
