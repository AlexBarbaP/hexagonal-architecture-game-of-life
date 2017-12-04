<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use Domain\Model\PopulateStrategies\PopulateStrategyInterface;
use Domain\Model\Rules\DeadGameRule;
use Domain\Model\Rules\PopulateGameRule;
use Domain\Model\Rules\RuleInterface;
use Domain\Model\Rules\SurvivalGameRule;

final class Game
{
    /** @var Board */
    private $board;

    /** @var RuleInterface[] */
    private $rules;

    /**
     * @param Size                      $size
     * @param PopulateStrategyInterface $populatorStrategy
     */
    public function __construct(Size $size, PopulateStrategyInterface $populatorStrategy)
    {
        $this->board = new Board($size, $populatorStrategy);

        $this->addRules();
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
     *
     */
    private function addRules(): void
    {
        $this->rules[] = new DeadGameRule();
        $this->rules[] = new SurvivalGameRule();
        $this->rules[] = new PopulateGameRule();
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
        $currentCellStatus = $cell->getCellStatus();

        $currentCellNeighbors = $this->board->getNeighbors($cell->getCoordinate());

        $newCellStatus = $this->executeRules($currentCellStatus, $currentCellNeighbors);

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
