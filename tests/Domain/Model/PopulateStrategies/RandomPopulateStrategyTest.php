<?php
declare(strict_types=1);

namespace Tests\Domain\Model\PopulateStrategies;

use Domain\Model\Cell;
use Domain\Model\CellStatus;
use Domain\Model\Coordinate;
use Domain\Model\PopulateStrategies\RandomPopulateStrategy;
use PHPUnit\Framework\TestCase;

class RandomPopulateStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function random_populate_strategy_should_return_a_randomly_populated_array()
    {
        $randomPopulateStrategy = new RandomPopulateStrategy();

        $cellGrid = $this->getCellGrid();

        $randomlyPopulatedGrid = $randomPopulateStrategy->populate($cellGrid);

        list($existsUnpopulated, $existsPopulated) = $this->lookForCellStatus($randomlyPopulatedGrid);

        $this->assertCount(4, $randomlyPopulatedGrid);
        $this->assertCount(4, $randomlyPopulatedGrid[0]);
        $this->assertSame(true, $existsUnpopulated);
        $this->assertSame(true, $existsPopulated);
    }

    /**
     * @param $randomlyPopulatedGrid
     *
     * @return array
     */
    private function lookForCellStatus($randomlyPopulatedGrid): array
    {
        $existsUnpopulated = false;
        $existsPopulated   = false;

        foreach ($randomlyPopulatedGrid as $row) {
            /** @var Cell $cell */
            foreach ($row as $cell) {
                if ($cell->getCellStatus()() == CellStatus::UNPOPULATED) {
                    $existsUnpopulated = true;
                }

                if ($cell->getCellStatus()() == CellStatus::POPULATED) {
                    $existsPopulated = true;
                }
            }
        }
        return array($existsUnpopulated, $existsPopulated);
    }

    private function getCellGrid(): array
    {
        return [
            [
                new Cell(new Coordinate(0, 0), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(0, 1), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(0, 2), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(0, 3), new CellStatus(CellStatus::POPULATED)),
            ],
            [
                new Cell(new Coordinate(1, 0), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(1, 1), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(1, 2), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(1, 3), new CellStatus(CellStatus::POPULATED)),
            ],
            [
                new Cell(new Coordinate(2, 0), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(2, 1), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(2, 2), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(2, 3), new CellStatus(CellStatus::POPULATED)),
            ],
            [
                new Cell(new Coordinate(3, 0), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(3, 1), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(3, 2), new CellStatus(CellStatus::POPULATED)),
                new Cell(new Coordinate(3, 3), new CellStatus(CellStatus::POPULATED)),
            ],
        ];
    }
}
