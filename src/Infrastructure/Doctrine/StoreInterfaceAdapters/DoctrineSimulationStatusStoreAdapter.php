<?php
declare(strict_types=1);

namespace Infrastructure\Doctrine\StoreInterfaceAdapters;

use Doctrine\ORM\EntityManager;
use Domain\Model\Entities\SimulationStatus;
use Domain\Model\Ports\SimulationStatusStoreInterface;

class DoctrineSimulationStatusStoreAdapter implements SimulationStatusStoreInterface
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
     * @param SimulationStatus $simulationStatus
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(SimulationStatus $simulationStatus): void
    {
        $this->em->persist($simulationStatus);
        $this->em->flush();
    }
}
