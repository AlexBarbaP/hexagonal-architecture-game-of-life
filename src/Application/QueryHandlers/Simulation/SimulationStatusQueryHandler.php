<?php
declare(strict_types=1);

namespace App\Application\QueryHandlers\Simulation;

use App\Domain\Model\Board;
use App\Domain\Model\Ports\SimulationStatusRepositoryInterface;
use App\Domain\Model\Ports\SimulationStatusStoreInterface;
use App\Domain\Model\Queries\Simulation\SimulationStatusQuery;

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
