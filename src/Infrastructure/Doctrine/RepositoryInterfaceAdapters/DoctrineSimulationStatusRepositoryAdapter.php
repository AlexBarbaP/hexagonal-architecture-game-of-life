<?php
declare(strict_types=1);

namespace Infrastructure\Doctrine\RepositoryInterfaceAdapters;

use Doctrine\ORM\EntityManager;
use Domain\Exception\EntityNotFoundException;
use Domain\Model\Entities\SimulationStatus;
use Domain\Model\Entities\SimulationStatusId;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;

class DoctrineSimulationStatusRepositoryAdapter implements SimulationStatusRepositoryInterface
{
    /** @var EntityManager */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param SimulationStatusId $simulationStatusId
     * @return SimulationStatus
     *
     * @throws EntityNotFoundException
     */
    public function find(SimulationStatusId $simulationStatusId): SimulationStatus
    {
        /** @var SimulationStatus $simulationStatus */
        $simulationStatus = $this->em->getRepository(SimulationStatus::class)->find($simulationStatusId);

        if (is_null($simulationStatus)) {
            throw new EntityNotFoundException("SimulationStatus with id: $simulationStatusId not found.");
        }

        return $simulationStatus;
    }

    /**
     * @return SimulationStatus[]
     */
    public function findAll(): array
    {
        $simulationCollection = $this->em->getRepository(SimulationStatus::class)->findAll();

        return $simulationCollection;
    }
}
