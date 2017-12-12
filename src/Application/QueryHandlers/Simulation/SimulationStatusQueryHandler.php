<?php
declare(strict_types=1);

namespace Application\QueryHandlers\Simulation;

use Application\Queries\Simulation\SimulationStatusQuery;
use Domain\Model\Board;

class SimulationStatusQueryHandler
{
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
