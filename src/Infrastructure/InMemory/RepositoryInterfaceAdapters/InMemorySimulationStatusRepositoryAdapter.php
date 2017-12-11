<?php
declare(strict_types=1);

namespace Infrastructure\InMemory\RepositoryInterfaceAdapters;

use Domain\Exception\EntityNotFoundException;
use Domain\Model\Entities\SimulationStatus;
use Domain\Model\Entities\SimulationStatusId;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;

class InMemorySimulationStatusRepositoryAdapter implements SimulationStatusRepositoryInterface
{
    /** @var SimulationStatus[] */
    private $inMemoryData = [];

    /**
     * @param SimulationStatus[] $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $simulationStatusItem) {
            $this->inMemoryData[$simulationStatusItem->getId()->id()] = $simulationStatusItem;
        }
    }

    /**
     * @param SimulationStatusId $simulationStatusId
     * @return SimulationStatus
     *
     * @throws EntityNotFoundException
     */
    public function find(SimulationStatusId $simulationStatusId): SimulationStatus
    {
        $simulationStatusIdString = $simulationStatusId->id();

        if (!array_key_exists($simulationStatusIdString, $this->inMemoryData)) {
            throw new EntityNotFoundException("SimulationStatus with id: $simulationStatusIdString not found.");
        }

        return $this->inMemoryData[$simulationStatusIdString];
    }

    /**
     * @return SimulationStatus[]
     */
    public function findAll(): array
    {
        return $this->inMemoryData;
    }
}
