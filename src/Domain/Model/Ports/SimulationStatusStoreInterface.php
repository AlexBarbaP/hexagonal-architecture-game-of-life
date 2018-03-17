<?php
declare(strict_types=1);

namespace App\Domain\Model\Ports;

use App\Domain\Model\Entities\SimulationStatus;

interface SimulationStatusStoreInterface
{
    /**
     * @param SimulationStatus $simulationStatus
     */
    public function save(SimulationStatus $simulationStatus): void;
}
