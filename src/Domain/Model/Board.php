<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Exception\InvalidSizeException;
use Domain\Model\PopulateStrategies\PopulateStrategyInterface;

final class Board
{
    /** @var array */
    private $grid = [];

    /** @var Size */
    private $size;

    /**
     * @param Size $size
     */
    public function __construct(Size $size)
    {
        $this->grid = $this->initializeEmptyGrid($size);

        $this->size             = clone $size;
    }

    /**
     * @param Coordinate $coordinate
     *
     * @return Cell
     *
     * @throws InvalidSizeException
     */
    public function getCell(Coordinate $coordinate): Cell
    {
        if (!isset($this->grid[$coordinate->getY()]) || !isset($this->grid[$coordinate->getY()][$coordinate->getX()])) {
            throw new InvalidSizeException();
        }

        $cell = $this->grid[$coordinate->getY()][$coordinate->getX()];

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
     * @param Size $size
     *
     * @return array
     */
    private function initializeEmptyGrid(Size $size): array
    {
        $grid = $this->grid;

        for ($y = 0; $y < $size->getHeight(); $y++) {
            $grid[] = [];

            for ($x = 0; $x < $size->getWidth(); $x++) {
                $coordinate = new Coordinate($x, $y);
                $cellStatus = new CellStatus(Cell::UNPOPULATED);

                $grid[$y][$x] = new Cell($coordinate, $cellStatus);
            }
        }

        return $grid;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return clone $this->size;
    }
}
