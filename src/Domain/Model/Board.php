<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Exception\InvalidSizeException;

final class Board
{
    /**
     * @param int $height
     * @param int $width
     */
    public function __construct(int $height, int $width)
    {
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return Cell
     *
     * @throws InvalidSizeException
     */
    public function getCell(int $x, int $y): Cell
    {
    }

    /**
     * @return array
     */
    public function getGrid(): array
    {
    }

    /**
     * @param array $grid
     *
     * @return Board
     */
    public function setGrid(array $grid): Board
    {
    }
}
