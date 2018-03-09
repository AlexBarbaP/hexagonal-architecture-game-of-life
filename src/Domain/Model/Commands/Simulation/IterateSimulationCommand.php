<?php
declare(strict_types=1);

namespace Domain\Model\Commands\Simulation;

use Domain\Model\Simulation;

class IterateSimulationCommand
{
    /** @var Simulation */
    private $simulation;

    /**
     * @param Simulation $simulation
     */
    public function __construct(Simulation $simulation)
    {
        $this->simulation = clone $simulation;
    }

    /**
     * @return Simulation
     */
    public function simulation()
    {
        return clone $this->simulation;
    }
}
