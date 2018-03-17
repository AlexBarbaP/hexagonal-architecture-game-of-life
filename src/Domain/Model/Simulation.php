<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\Events\SimulationInitializedEvent;
use App\Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use App\Domain\Model\PopulateStrategies\PopulateStrategyInterface;
use App\Domain\Model\Rules\DeadSimulationRule;
use App\Domain\Model\Rules\PopulateSimulationRule;
use App\Domain\Model\Rules\RuleInterface;
use App\Domain\Model\Rules\SurvivalSimulationRule;
use League\Event\EmitterInterface;
use Ramsey\Uuid\Uuid;

final class Simulation
{
    /** @var Board */
    private $board;

    /** @var RuleInterface[] */
    private $rules;

    /** @var EmitterInterface */
    private $eventBus;

    /**
     * @param Size                      $size
     * @param PopulateStrategyInterface $populateStrategy
     * @param EmitterInterface          $eventBus
     */
    public function __construct(
        Size $size,
        PopulateStrategyInterface $populateStrategy,
        EmitterInterface $eventBus
    ) {
        $this->eventBus = $eventBus;

        $this->board = new Board($size, $populateStrategy);

        $this->addRules();

        $event = new SimulationInitializedEvent(Uuid::uuid4()->toString(), $this->getBoard());

        $this->eventBus->emit($event);
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
     * @param int        $cellNeighbors
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
}
