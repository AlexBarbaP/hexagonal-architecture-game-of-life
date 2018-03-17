<?php
declare(strict_types=1);

namespace Tests\Domain\Model\PopulateStrategies;

use App\Domain\Model\Cell;
use App\Domain\Model\CellStatus;
use App\Domain\Model\Coordinate;
use App\Domain\Model\Entities\SimulationStatus;
use App\Domain\Model\Entities\SimulationStatusId;
use App\Domain\Model\PopulateStrategies\FromStoragePopulateStrategy;
use App\Infrastructure\InMemory\RepositoryInterfaceAdapters\InMemorySimulationStatusRepositoryAdapter;
use PHPUnit\Framework\TestCase;

class FromStoragePopulateStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function from_storage_populate_strategy_should_load_initial_board_from_storage()
    {
        $simulationStatusId    = SimulationStatusId::create();
        $simulationStatusArray = $this->getSimulationStatusArray([
            [0, 1],
            [1, 0],
        ], $simulationStatusId);

        $inMemoryRepositoryAdapter   = new InMemorySimulationStatusRepositoryAdapter($simulationStatusArray);
        $fromStoragePopulateStrategy = new FromStoragePopulateStrategy($inMemoryRepositoryAdapter, $simulationStatusId);

        $boardGrid = $this->getBoardGrid();

        /** @var Cell[][] $populatedGrid */
        $populatedGrid = $fromStoragePopulateStrategy->populate($boardGrid);

        $this->assertCount(2, $populatedGrid);
        $this->assertCount(2, $populatedGrid[0]);
        $this->assertInstanceOf(Cell::class, $populatedGrid[0][0]);

        $this->assertSame(Cell::UNPOPULATED, ($populatedGrid[0][0])->getCellStatus()());
        $this->assertSame(Cell::POPULATED, ($populatedGrid[0][1])->getCellStatus()());
        $this->assertSame(Cell::POPULATED, ($populatedGrid[1][0])->getCellStatus()());
        $this->assertSame(Cell::UNPOPULATED, ($populatedGrid[1][1])->getCellStatus()());
    }

    /**
     * @test
     *
     * @expectedException App\Domain\Exception\InvalidSizeException
     */
    public function fixed_populate_strategy_should_throw_exception_for_one_dimension_array()
    {
        $simulationStatusId    = SimulationStatusId::create();
        $simulationStatusArray = $this->getSimulationStatusArray([], $simulationStatusId);

        $inMemoryRepositoryAdapter   = new InMemorySimulationStatusRepositoryAdapter($simulationStatusArray);
        $fromStoragePopulateStrategy = new FromStoragePopulateStrategy($inMemoryRepositoryAdapter, $simulationStatusId);

        $boardGrid = $this->getBoardGrid();

        /** @var Cell[][] $populatedGrid */
        $fromStoragePopulateStrategy->populate($boardGrid);
    }

    /**
     * @param array              $statusArray
     * @param SimulationStatusId $simulationStatusId
     *
     * @return array
     */
    private function getSimulationStatusArray(array $statusArray, SimulationStatusId $simulationStatusId): array
    {
        $serializedSimulationStatus = serialize($statusArray);

        return [
            new SimulationStatus(
                $simulationStatusId,
                $serializedSimulationStatus
            ),
        ];
    }

    /**
     * @return array
     */
    private function getBoardGrid(): array
    {
        $boardGrid = [
            [
                new Cell(new Coordinate(0, 0), new CellStatus(CellStatus::UNPOPULATED)),
                new Cell(new Coordinate(0, 1), new CellStatus(CellStatus::UNPOPULATED)),
            ],
            [
                new Cell(new Coordinate(1, 0), new CellStatus(CellStatus::UNPOPULATED)),
                new Cell(new Coordinate(1, 1), new CellStatus(CellStatus::UNPOPULATED)),
            ],
        ];

        return $boardGrid;
    }
}
