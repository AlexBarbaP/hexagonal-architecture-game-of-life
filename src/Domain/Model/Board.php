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

    /** @var PopulateStrategyInterface */
    private $populateStrategy;

    /**
     * @param Size $size
     * @param PopulateStrategyInterface $populateStrategy
     */
    public function __construct(Size $size, PopulateStrategyInterface $populateStrategy)
    {
        $this->grid = $this->initializeEmptyGrid($size);

        $this->size             = clone $size;
        $this->populateStrategy = clone $populateStrategy;

        $this->grid = $populateStrategy->populate($this->grid);
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
     * @param Coordinate $cellCoordinate
     *
     * @return int
     */
    public function getNeighbors(Coordinate $cellCoordinate): int
    {
        $neighbours = 0;

        for ($yOffset = -1; $yOffset < 2; $yOffset++) {
            $neighbours += $this->getRowNeighbors($cellCoordinate, $yOffset);
        }

        return $neighbours;
    }

    /**
     * @param Coordinate $cellCoordinate
     * @param int $yOffset
     *
     * @return int
     */
    private function getRowNeighbors(Coordinate $cellCoordinate, int $yOffset): int
    {
        $neighbours = 0;

        for ($xOffset = -1; $xOffset < 2; $xOffset++) {
            if (!$this->isEmptyNeighbor($cellCoordinate, $yOffset, $xOffset)) {
                $neighbours++;
            }
        }

        return $neighbours;
    }

    /**
     * @param Coordinate $cellCoordinate
     * @param int $xOffset
     * @param            $yOffset
     *
     * @return bool
     */
    private function isEmptyNeighbor(Coordinate $cellCoordinate, int $yOffset, $xOffset): bool
    {
        try {
            $neighborX = $xOffset + $cellCoordinate->getX();
            $neighborY = $yOffset + $cellCoordinate->getY();

            $neighborCoordinate = new Coordinate($neighborX, $neighborY);

            $samePositionCells = $cellCoordinate->equals($neighborCoordinate);

            if ($samePositionCells) {
                return true;
            }

            $neighborStatus = $this->getCell($neighborCoordinate)->getCellStatus();

            if ($neighborStatus() == CellStatus::POPULATED) {
                return false;
            }
        } catch (\Exception $t) {
            // no behaviour required here
        }

        return true;
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
     * @return Size
     */
    public function getSize(): Size
    {
        return clone $this->size;
    }

    /**
     * @return bool
     */
    public function isAnyPopulatedCell(): bool
    {
        $anyPopulated = false;

        foreach ($this->getGrid() as $row) {
            if ($anyPopulated) {
                break;
            }

            $anyPopulated = $this->isAnyPopulatedCellInRow($row);
        }

        return $anyPopulated;
    }

    /**
     * @return array
     */
    public function getGrid(): array
    {
        return $this->grid;
    }

    /**
     * @param array $row
     *
     * @return bool
     */
    private function isAnyPopulatedCellInRow(array $row): bool
    {
        $anyPopulated = false;

        /** @var Cell $cell */
        foreach ($row as $cell) {
            $cellStatus = $cell->getCellStatus();

            if ($cellStatus() == CellStatus::POPULATED) {
                $anyPopulated = true;
                break;
            }
        }

        return $anyPopulated;
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
}
