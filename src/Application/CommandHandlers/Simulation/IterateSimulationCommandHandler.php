<?php
declare(strict_types=1);

namespace Application\CommandHandlers\Simulation;

use Application\Commands\Simulation\IterateSimulationCommand;
use Domain\Model\Simulation;

class IterateSimulationCommandHandler
{
    /**
     * @param IterateSimulationCommand $command
     *
     * @return Simulation
     */
    public function handle(IterateSimulationCommand $command): Simulation
    {
        $simulation = $command->simulation();

        $simulation->iterate();

        return $simulation;
    }
}
