<?php
declare(strict_types=1);

namespace App\Domain\Model\Ports;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Entities\SimulationStatus;
use App\Domain\Model\Entities\SimulationStatusId;

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
