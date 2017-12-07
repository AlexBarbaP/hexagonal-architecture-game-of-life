<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Model\Entities\GameStatus;
use Domain\Model\Entities\GameStatusId;
use Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use Domain\Model\PopulateStrategies\PopulateStrategyInterface;
use Domain\Model\Ports\GameStatusRepositoryInterface;
use Domain\Model\Ports\GameStatusStoreInterface;
use Domain\Model\Rules\DeadSimulationRule;
use Domain\Model\Rules\PopulateSimulationRule;
use Domain\Model\Rules\RuleInterface;
use Domain\Model\Rules\SurvivalSimulationRule;

final class Simulation
{
    /** @var Board */
    private $board;

    /** @var RuleInterface[] */
    private $rules;

    /** @var GameStatusRepositoryInterface */
    private $gameStatusRepository;

    /** @var GameStatusStoreInterface */
    private $gameStatusStore;

    /**
     * @param Size $size
     * @param PopulateStrategyInterface $populateStrategy
     */
    public function __construct(
        Size $size,
        PopulateStrategyInterface $populateStrategy,
        GameStatusRepositoryInterface $gameStatusRepository,
        GameStatusStoreInterface $gameStatusStore
    ) {
        $this->gameStatusRepository = $gameStatusRepository;
        $this->gameStatusStore      = $gameStatusStore;

        $this->board = new Board($size, $populateStrategy);

        $this->addRules();

        $this->storeInitialGameStatus();
    }

    /**
     *
     */
    private function addRules(): void
    {
        $this->rules[] = new DeadSimulationRule();
        $this->rules[] = new SurvivalSimulationRule();
        $this->rules[] = new PopulateSimulationRule();
    }

    /**
     *
     */
    private function storeInitialGameStatus(): void
    {
        $gameStatusId = GameStatusId::create();
        $status       = serialize($this->board->toArray());

        $gameStatus = new GameStatus($gameStatusId, $status);

        $this->gameStatusStore->save($gameStatus);
    }

    /**
     *
     */
    public function iterate(): void
    {
        $newGrid = $this->iterateBoard($this->getBoard()->getGrid());

        $fixedPopulateStrategy = new FixedPopulateStrategy($newGrid);

        $this->board = new Board($this->board->getSize(), $fixedPopulateStrategy);
    }

    /**
     * @param array $grid
     *
     * @return array
     */
    private function iterateBoard(array $grid): array
    {
        $newGrid = [];

        foreach ($grid as $row) {
            $newGrid[] = [];

            /** @var Cell $cell */
            foreach ($row as $cell) {
                $nextIterationCellStatus = $this->getNextIterationCellStatus($cell);

                $newGrid[count($newGrid) - 1][] = $nextIterationCellStatus();
            }
        }

        return $newGrid;
    }

    /**
     * @param Cell $cell
     *
     * @return CellStatus
     */
    private function getNextIterationCellStatus(Cell $cell): CellStatus
    {
        $cellCurrentStatus = $cell->getCellStatus();

        $cellNeighbors = $this->board->getNeighbors($cell->getCoordinate());

        $newCellStatus = $this->executeRules($cellCurrentStatus, $cellNeighbors);

        return $newCellStatus;
    }

    /**
     * @param CellStatus $cellStatus
     * @param int $cellNeighbors
     *
     * @return CellStatus
     */
    private function executeRules(CellStatus $cellStatus, int $cellNeighbors): CellStatus
    {
        foreach ($this->rules as $rule) {
            if ($rule->match($cellStatus, $cellNeighbors)) {
                $newCellStatus = $rule->execute();

                return $newCellStatus;
            }
        }

        return $cellStatus;
    }

    /**
     * @return Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        $anyPopulatedCell = $this->getBoard()->isAnyPopulatedCell();

        return !$anyPopulatedCell;
    }
}
