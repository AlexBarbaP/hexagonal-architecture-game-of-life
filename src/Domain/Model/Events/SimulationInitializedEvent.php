<?php
declare(strict_types=1);

namespace App\Domain\Model\Events;

use App\Domain\Model\Board;

final class SimulationInitializedEvent extends EventAbstract
{
    /** @var Board */
    private $board;

    /**
     * @param string $id
     * @param Board  $board
     */
    public function __construct(string $id, Board $board)
    {
        parent::__construct($id);

        $this->board = clone $board;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Event.Simulation.Initialized';
    }

    /**
     * @return Board
     */
    public function getBoard(): Board
    {
        return clone $this->board;
    }
}
