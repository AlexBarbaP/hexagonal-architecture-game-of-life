<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Exception\InvalidCoordinateException;
use Domain\Exception\InvalidSizeException;
use Domain\Model\PopulateStrategies\PopulateStrategyInterface;

final class Board
{
    /** @var array */
    private $grid = [];

    /** @var Size */
    private $size;

    /** @var PopulateStrategyInterface */
    private $populateStrategy;

    /**
     * @param Size                      $size
     * @param PopulateStrategyInterface $populatorStrategy
     */
    public function __construct(Size $size, PopulateStrategyInterface $populatorStrategy)
    {
        $this->grid = $this->initializeEmptyGrid($size);

        $this->size             = clone $size;
        $this->populateStrategy = clone $populatorStrategy;

        $this->grid = $populatorStrategy->populate($this->grid);
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
        if (!isset($this->grid[$coordinate->getY()]) || !isset($this->grid[$coordinate->getX()])) {
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
        $grid = $this->grid;

        return $grid;
    }

    public function getNeighbors(Coordinate $coordinate): int
    {
        $neighbours = 0;

        for ($xOffset = -1; $xOffset < 2; $xOffset++) {
            $neighbours += $this->getRowNeighbors($coordinate, $xOffset);
        }

        return $neighbours;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $gridArray = [];

        foreach ($this->getGrid() as $row) {
            $gridArray[] = [];

            /** @var Cell $cell */
            foreach ($row as &$cell) {
                $cellStatus = $cell->getCellStatus();

                $gridArray[count($gridArray) - 1][] = $cellStatus();
            }
        }

        return $gridArray;
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
     * @param Coordinate $coordinate
     * @param int        $xOffset
     *
     * @return int
     */
    private function getRowNeighbors(Coordinate $coordinate, int $xOffset): int
    {
        $neighbours = 0;

        for ($yOffset = -1; $yOffset < 2; $yOffset++) {
            if (!$this->isEmptyNeighbor($coordinate, $xOffset, $yOffset)) {
                $neighbours++;
            }
        }

        return $neighbours;
    }

    /**
     * @param Coordinate $coordinate
     * @param int        $xOffset
     * @param            $yOffset
     *
     * @return bool
     */
    private function isEmptyNeighbor(Coordinate $coordinate, int $xOffset, $yOffset): bool
    {
        try {
            $neighborX = $xOffset + $coordinate->getX();
            $neighborY = $yOffset + $coordinate->getY();

            $neighborCoordinate = new Coordinate($neighborX, $neighborY);

            if (!$coordinate->equals($neighborCoordinate) && $this->getCell($neighborCoordinate)->getCellStatus()()) {
                return false;
            }
        } catch (InvalidCoordinateException | InvalidSizeException $e) {
        }

        return true;
    }
}
