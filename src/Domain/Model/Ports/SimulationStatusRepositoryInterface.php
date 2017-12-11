<?php
declare(strict_types=1);

namespace Domain\Model\Ports;

use Domain\Exception\EntityNotFoundException;
use Domain\Model\Entities\SimulationStatus;
use Domain\Model\Entities\SimulationStatusId;

interface SimulationStatusRepositoryInterface
{
    /**
     * @param SimulationStatusId $simulationStatusId
     * @return SimulationStatus
     *
     * @throws EntityNotFoundException
     */
    public function find(SimulationStatusId $simulationStatusId): SimulationStatus;

    /**
     * @return SimulationStatus[]
     */
    public function findAll(): array;
}
