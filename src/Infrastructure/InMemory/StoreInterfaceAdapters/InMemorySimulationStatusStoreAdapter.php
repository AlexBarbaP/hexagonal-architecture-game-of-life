<?php
declare(strict_types=1);

namespace App\Infrastructure\InMemory\StoreInterfaceAdapters;

use App\Domain\Model\Entities\SimulationStatus;
use App\Domain\Model\Ports\SimulationStatusStoreInterface;

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
