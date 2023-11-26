<?php

namespace DoekeNorg\TicTacToe\Tests;

use DoekeNorg\TicTacToe\AsciiGridOutput;
use DoekeNorg\TicTacToe\Grid;
use DoekeNorg\TicTacToe\Mark;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see AsciiGridOutput}
 * @since $ver$
 */
final class AsciiGridOutputTest extends TestCase
{
    /**
     * Test case for {@see AsciiGridOutput::render()}.
     * @since $ver$
     */
    public function testOutput(): void
    {
        $grid = Grid::empty();
        $grid = $grid->placeMark(1, Mark::X);
        $grid = $grid->placeMark(2, Mark::O);

        $ascii = new AsciiGridOutput();
        $output = $ascii->render($grid);

        self::assertSame(
            <<<GRID
+---+---+---+
| 1 | \e[1;34mX\e[0m | \e[1;32mO\e[0m |
+---+---+---+
| 4 | 5 | 6 |
+---+---+---+
| 7 | 8 | 9 |
+---+---+---+
GRID
            ,
            $output,
        );
    }
}
