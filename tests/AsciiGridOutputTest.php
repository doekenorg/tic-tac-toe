<?php


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
     * Test case for {@see AsciiGridOutput::output()}.
     * @since $ver$
     */
    public function testOutput(): void
    {
        $grid = Grid::empty();
        $grid->placeMark(1, Mark::X);
        $grid->placeMark(2, Mark::O);
        $ascii = new AsciiGridOutput();
        $ascii->output($grid);

        self::assertSame(
            <<<GRID
+---+---+---+
|   | X | O |
+---+---+---+
|   |   |   |
+---+---+---+
|   |   |   |
+---+---+---+
GRID
            ,
            $ascii->getOutput()
        );
    }
}
