<?php


use DoekeNorg\TicTacToe\Grid;
use DoekeNorg\TicTacToe\Mark;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see Grid}
 * @since $ver$
 */
final class GridTest extends TestCase
{
    /**
     * Test case for {@see Grid::placeMark()}.
     * @since $ver$
     */
    public function testPlaceMark(): void
    {
        $grid = Grid::empty();
        $new = $grid->placeMark(1, Mark::X);
        self::assertNotSame($grid, $new);
    }

    /**
     * Test case for {@see Grid::placeMark()}.
     * @since $ver$
     */
    public function testPlaceOutOfBoundsMark(): void
    {
        $this->expectException(\RuntimeException::class);
        $grid = Grid::empty();
        $grid->placeMark(10, Mark::X);
    }

    /**
     * Test case for {@see Grid::placeMark()}.
     * @since $ver$
     */
    public function testPlaceOnNonEmptySpace(): void
    {
        $this->expectException(\RuntimeException::class);
        $grid = Grid::empty();
        $grid->placeMark(1, Mark::X);
        $grid->placeMark(1, Mark::O);
    }

    public function testGetSquares(): void
    {
        $grid = Grid::empty(2);
        $grid->placeMark(1, Mark::X);
        $grid->placeMark(2, Mark::O);

        self::assertSame([
            null,
            Mark::X,
            Mark::O,
            null,
        ], $grid->getSquares()->toArray());
    }
}
