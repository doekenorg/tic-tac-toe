# Tic Tac Toe Kata

This was a fun experiment on modeling a tiny game. The implementation is very basic; there are no players. But it
should resemble the structure of all the basic components of the game. 

## Grid
The grid only knows about squares and marks. It doesn't enforce any rules. Only that it cannot have a mark outside 
its size; and it won't place a mark on a filled square. The grid is also immutable.

## Game
The Game enforces the rules of tic-tac-toe. It also keeps track of which square has the turn. It only has a single
public command; `placeMark(int $square)` which places the square on the grid.

## GameEventListener
The `Game` receives a `GameEventListener` on which it calls all the possible events.

## Mark
A mark is a simple enum of either `X` or `O`.

## Rendering
For rendering, I opted for a CLI implementation. The `CliUserInterface` implements the `GameEventListener` and will
render the `Grid` for visualization.

## Usage

- Run `php index.php` for a simple 3x3 game.
- Run `php index.php 4` to make it a little more challenging. 
- Run `php index.php 1` or `php index.php 2` to always win with `X`.
