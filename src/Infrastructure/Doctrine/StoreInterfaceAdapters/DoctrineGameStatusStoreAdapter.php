<?php
declare(strict_types=1);

namespace BlogBoundedContext\Infrastructure\Doctrine\StoreInterfaceAdapters;
use Doctrine\ORM\EntityManager;
use Domain\Model\Entities\GameStatus;
use Domain\Model\Ports\GameStatusStoreInterface;

class DoctrineGameStatusStoreAdapter implements GameStatusStoreInterface
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
     * @param GameStatus $gameStatus
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(GameStatus $gameStatus): void
    {
        $this->em->persist($gameStatus);
        $this->em->flush();
    }
}
