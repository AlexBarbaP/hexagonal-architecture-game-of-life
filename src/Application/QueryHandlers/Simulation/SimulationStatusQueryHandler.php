<?php
declare(strict_types=1);

namespace Application\QueryHandlers\Simulation;

use Application\Queries\Simulation\SimulationStatusQuery;
use Domain\Model\Board;
use Domain\Model\Ports\GameStatusRepositoryInterface;
use Domain\Model\Ports\GameStatusStoreInterface;

class SimulationStatusQueryHandler
{
    /** @var GameStatusRepositoryInterface */
    private $gameStatusRepository;

    /** @var GameStatusStoreInterface */
    private $gameStatusStore;

    /**
     * @param GameStatusRepositoryInterface $gameStatusRepository
     * @param GameStatusStoreInterface      $gameStatusStore
     */
    public function __construct(
        GameStatusRepositoryInterface $gameStatusRepository,
        GameStatusStoreInterface $gameStatusStore
    ) {
        $this->gameStatusRepository = $gameStatusRepository;
        $this->gameStatusStore      = $gameStatusStore;
    }

    /**
     * @param SimulationStatusQuery $simulationStatusQuery
     *
     * @return Board
     */
    public function handle(SimulationStatusQuery $simulationStatusQuery): Board
    {
        $simulation = $simulationStatusQuery->simulation();

        return $simulation->getBoard();
    }
}
