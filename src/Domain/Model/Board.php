<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Exception\InvalidSizeException;

final class Board
{
    /** @var array */
    private $grid = [];

    /**
     * @param int $height
     * @param int $width
     */
    public function __construct(int $height, int $width)
    {
        $this->grid = $this->initializeEmptyGrid($height, $width);
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
        if (!isset($this->grid[$y]) || !isset($this->grid[$y][$x])) {
            throw new InvalidSizeException();
        }

        $cell = $this->grid[$y][$x];

        return $cell;
    }

    /**
     * @return array
     */
    public function getGrid(): array
    {
        return $this->grid;
    }

    /**
     * @param array $grid
     *
     * @return Board
     */
    public function setGrid(array $grid): Board
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * @param int $height
     * @param int $width
     *
     * @return array
     */
    private function initializeEmptyGrid(int $height, int $width): array
    {
        $grid = $this->grid;

        for ($y = 0; $y < $height; $y++) {
            $grid[] = [];

            for ($x = 0; $x < $width; $x++) {
                $grid[$y][$x] = new Cell(new CellStatus(Cell::UNPOPULATED));
            }
        }

        return $grid;
    }
}
