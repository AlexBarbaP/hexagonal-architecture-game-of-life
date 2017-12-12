<?php
declare(strict_types=1);

namespace Domain\Model;

final class Simulation
{
    /** @var Board */
    private $board;

    /**
     * @param int $height
     * @param int $width
     */
    public function __construct(int $height, int $width)
    {
        $this->board = new Board($height, $width);
    }

    /**
     *
     */
    public function iterate(): void
    {
        $newGrid = $this->iterateBoard($this->getBoard()->getGrid());

        $this->board = $this->board->setGrid($newGrid);
    }

    /**
     * @param array $grid
     *
     * @return array
     */
    private function iterateBoard(array &$grid): array
    {
        $newGrid = [];

        foreach ($grid as $row) {
            $newGrid[] = [];

            /** @var Cell $cell */
            foreach ($row as $cell) {
                $nextIterationCellStatus = $this->getNextIterationCellStatus($cell);

                $newGrid[count($newGrid) - 1][] = new Cell($nextIterationCellStatus);
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
        return new CellStatus(Cell::UNPOPULATED);
    }

    /**
     * @return Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }
}
