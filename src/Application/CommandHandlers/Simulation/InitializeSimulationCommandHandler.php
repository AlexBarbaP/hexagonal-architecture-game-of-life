<?php
declare(strict_types=1);

namespace Application\CommandHandlers\Simulation;

use Application\Commands\Simulation\InitializeSimulationCommand;
use Domain\Model\Simulation;
use Domain\Model\Size;

class InitializeSimulationCommandHandler
{
    /**
     * @param InitializeSimulationCommand $command
     *
     * @return Simulation
     */
    public function handle(InitializeSimulationCommand $command): Simulation
    {
        $size             = new Size($command->height(), $command->width());
        $populateStrategy = $command->populateStrategy();

        $simulation = new Simulation($size, $populateStrategy);

        return $simulation;
    }
}
