<?php
declare(strict_types=1);

namespace Tests\Domain\Model\PopulateStrategies;

use Domain\Model\Cell;
use Domain\Model\CellStatus;
use Domain\Model\Coordinate;
use Domain\Model\Entities\GameStatus;
use Domain\Model\Entities\GameStatusId;
use Domain\Model\PopulateStrategies\FromStoragePopulateStrategy;
use Infrastructure\InMemory\RepositoryInterfaceAdapters\InMemoryGameStatusRepositoryAdapter;
use PHPUnit\Framework\TestCase;

class FromStoragePopulateStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function from_storage_populate_strategy_should_load_initial_board_from_storage()
    {
        $gameStatusId    = GameStatusId::create();
        $gameStatusArray = $this->getGameStatusArray([
            [0, 1],
            [1, 0],
        ], $gameStatusId);

        $inMemoryRepositoryAdapter   = new InMemoryGameStatusRepositoryAdapter($gameStatusArray);
        $fromStoragePopulateStrategy = new FromStoragePopulateStrategy($inMemoryRepositoryAdapter, $gameStatusId);

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
     * @expectedException Domain\Exception\InvalidSizeException
     */
    public function fixed_populate_strategy_should_throw_exception_for_one_dimension_array()
    {
        $gameStatusId    = GameStatusId::create();
        $gameStatusArray = $this->getGameStatusArray([], $gameStatusId);

        $inMemoryRepositoryAdapter   = new InMemoryGameStatusRepositoryAdapter($gameStatusArray);
        $fromStoragePopulateStrategy = new FromStoragePopulateStrategy($inMemoryRepositoryAdapter, $gameStatusId);

        $boardGrid = $this->getBoardGrid();

        /** @var Cell[][] $populatedGrid */
        $fromStoragePopulateStrategy->populate($boardGrid);
    }

    /**
     * @param array        $statusArray
     * @param GameStatusId $gameStatusId
     *
     * @return array
     */
    private function getGameStatusArray(array $statusArray, GameStatusId $gameStatusId): array
    {
        $serializedGameStatus = serialize($statusArray);

        return [
            new GameStatus(
                $gameStatusId,
                $serializedGameStatus
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
