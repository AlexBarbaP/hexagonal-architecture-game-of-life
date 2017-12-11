<?php
declare(strict_types=1);

namespace Domain\Model\Ports;

use Domain\Model\Entities\SimulationStatus;

interface SimulationStatusStoreInterface
{
    /**
     * @param SimulationStatus $simulationStatus
     */
    public function save(SimulationStatus $simulationStatus): void;
}
