<?php
declare(strict_types=1);

namespace Application\QueryHandlers\Simulation;

use Domain\Model\Board;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;
use Domain\Model\Ports\SimulationStatusStoreInterface;
use Domain\Model\Queries\Simulation\SimulationStatusQuery;

class SimulationStatusQueryHandler
{
    /** @var SimulationStatusRepositoryInterface */
    private $simulationStatusRepository;

    /** @var SimulationStatusStoreInterface */
    private $simulationStatusStore;

    /**
     * @param SimulationStatusRepositoryInterface $simulationStatusRepository
     * @param SimulationStatusStoreInterface      $simulationStatusStore
     */
    public function __construct(
        SimulationStatusRepositoryInterface $simulationStatusRepository,
        SimulationStatusStoreInterface $simulationStatusStore
    ) {
        $this->simulationStatusRepository = $simulationStatusRepository;
        $this->simulationStatusStore      = $simulationStatusStore;
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
