<?php
declare(strict_types=1);

namespace Tests\Domain\Model;

use Domain\Model\Board;
use Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use Domain\Model\Size;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    /**
     * @test
     */
    public function board_to_array_should_return_an_array_render_of_board_grid()
    {
        $grid = [
            [1, 1, 1],
            [1, 1, 1],
            [1, 1, 1],
        ];
        $size = new Size(3, 3);

        $fixedPopulateStrategy = new FixedPopulateStrategy($grid);

        $board = new Board($size, $fixedPopulateStrategy);

        $boardGridArray = $board->toArray();

        $this->assertSame($grid, $boardGridArray);
    }
}
