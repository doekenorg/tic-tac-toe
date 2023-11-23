<?php

namespace DoekeNorg\TicTacToe\Tests;

use DoekeNorg\TicTacToe\Game;
use DoekeNorg\TicTacToe\Mark;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see Game}
 * @since $ver$
 */
final class GameTest extends TestCase
{
    /**
     * Test case for {@see Game::placeMark()}.
     * @since $ver$
     */
    public function testPlaceMark(): void
    {
        $listener = new TestGameListener();
        $game = Game::new($listener);
        $game->placeMark(0); // X
        $game->placeMark(4); // O
        $game->placeMark(1); // X
        $game->placeMark(5); // O
        $game->placeMark(2); // X

        self::assertEquals([
            Mark::X,
            Mark::X,
            Mark::X,
            null,
            Mark::O,
            Mark::O,
            null,
            null,
            null,
        ], $listener->getLastGrid()->getSquares()->toArray());
    }

    public function testPlaceMarkAfterFinished(): void
    {
        $this->expectException(\RuntimeException::class);
        $game = Game::new(new TestGameListener());
        $game->placeMark(0); // X
        $game->placeMark(4); // O
        $game->placeMark(1); // X
        $game->placeMark(5); // O
        $game->placeMark(2); // X Winner

        $game->placeMark(6); // O tries another move.
    }

    public static function movesProvider(): array
    {
        return [
            'no moves' => [
                'size' => 3,
                'moves' => [],
                'winner' => null,
                'is_finished' => false,
                'is_draw' => false,
            ],
            'X winner' => [
                'size' => 3,
                'moves' => [0, 4, 1, 5, 2],
                'winner' => Mark::X,
                'is_finished' => true,
                'is_draw' => false,
            ],
            'X winner diagonal' => [
                'size' => 3,
                'moves' => [4, 5, 6, 2, 8, 7, 0],
                'winner' => Mark::X,
                'is_finished' => true,
                'is_draw' => false,
            ],
            'O winner' => [
                'size' => 3,
                'moves' => [4, 0, 6, 2, 5, 1],
                'winner' => Mark::O,
                'is_finished' => true,
                'is_draw' => false,
            ],
            'draw' => [
                'size' => 3,
                'moves' => [0, 5, 8, 4, 3, 6, 2, 1, 7],
                'winner' => null,
                'is_finished' => true,
                'is_draw' => true,
            ],
            'X Winner 2x2' => [
                'size' => 2,
                'moves' => [0, 1, 2],
                'winner' => Mark::X,
                'is_finished' => true,
                'is_draw' => false,
            ],
            'X Winner 1x1' => [
                'size' => 1,
                'moves' => [0],
                'winner' => Mark::X,
                'is_winner' => true,
                'is_draw' => false,
            ],
        ];
    }

    /**
     * @dataProvider movesProvider
     */
    public function testFindWinner(
        ?int $size,
        array $moves,
        ?Mark $expected_winner,
        bool $is_finished,
        bool $is_draw
    ): void {
        $listener = new TestGameListener();
        $game = Game::new($listener, $size);
        foreach ($moves as $square) {
            $game->placeMark($square);
        }

        self::assertSame($expected_winner, $listener->findWinner());
        self::assertSame($is_finished, $listener->isFinished());
        self::assertSame($is_draw, $listener->isDraw());
    }
}
