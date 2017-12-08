<?php
declare(strict_types=1);

namespace Domain\Model\PopulateStrategies;

use Domain\Exception\InvalidGridException;
use Domain\Exception\InvalidSizeException;
use Domain\Model\Cell;
use Domain\Model\CellStatus;
use Domain\Model\Coordinate;

final class FixedPopulateStrategy implements PopulateStrategyInterface
{
    /**
     * @var array
     */
    private $grid = [];

    /**
     * @param array $grid
     *
     * @throws InvalidGridException
     */
    public function __construct(array $grid)
    {
        if (count($grid) <= 0) {
            throw new InvalidGridException();
        }

        if (count($grid[0]) <= 0) {
            throw new InvalidGridException();
        }

        $this->grid = $grid;
    }

    /**
     * @param array $boardGrid
     *
     * @return array
     *
     * @throws InvalidSizeException
     */
    public function populate(array $boardGrid): array
    {
        for ($y = 0; $y < count($boardGrid); $y++) {
            $boardGrid = $this->populateRow($boardGrid, $y);
        }

        return $boardGrid;
    }

    /**
     * @param array $grid
     * @param       $rowIndex
     *
     * @return array
     *
     * @throws InvalidSizeException
     */
    private function populateRow(array $grid, $rowIndex): array
    {
        for ($x = 0; $x < count($grid[$rowIndex]); $x++) {
            $grid = $this->populateCell($grid, $rowIndex, $x);
        }

        return $grid;
    }

    /**
     * @param array $grid
     * @param       $rowIndex
     * @param       $cellIndex
     *
     * @return array
     *
     * @throws InvalidSizeException
     */
    private function populateCell(array $grid, $rowIndex, $cellIndex): array
    {
        if (!isset($this->grid[$rowIndex][$cellIndex])) {
            throw new InvalidSizeException();
        }

        $coordinate = new Coordinate($cellIndex, $rowIndex);
        $cellStatus = new CellStatus($this->grid[$rowIndex][$cellIndex]);

        $grid[$rowIndex][$cellIndex] = new Cell($coordinate, $cellStatus);

        return $grid;
    }
}
