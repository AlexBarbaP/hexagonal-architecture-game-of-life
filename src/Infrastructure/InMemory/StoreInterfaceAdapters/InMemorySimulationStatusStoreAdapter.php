<?php
declare(strict_types=1);

namespace Infrastructure\InMemory\StoreInterfaceAdapters;

use Domain\Model\Entities\SimulationStatus;
use Domain\Model\Ports\SimulationStatusStoreInterface;

class InMemorySimulationStatusStoreAdapter implements SimulationStatusStoreInterface
{
    /** @var SimulationStatus[] */
    private static $inMemoryData = [];

    /**
     * @param SimulationStatus $simulationStatus
     */
    public function save(SimulationStatus $simulationStatus): void
    {
        $simulationStatusIdString = $simulationStatus->getId()->id();

        self::$inMemoryData[$simulationStatusIdString] = $simulationStatus;
    }
}
