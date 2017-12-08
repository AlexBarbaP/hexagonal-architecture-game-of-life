<?php
declare(strict_types=1);

namespace Infrastructure\Doctrine\RepositoryInterfaceAdapters;

use Doctrine\ORM\EntityManager;
use Domain\Exception\EntityNotFoundException;
use Domain\Model\Entities\GameStatus;
use Domain\Model\Entities\GameStatusId;
use Domain\Model\Ports\GameStatusRepositoryInterface;

class DoctrineGameStatusRepositoryAdapter implements GameStatusRepositoryInterface
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
     * @param GameStatusId $gameStatusId
     * @return GameStatus
     *
     * @throws EntityNotFoundException
     */
    public function find(GameStatusId $gameStatusId): GameStatus
    {
        /** @var GameStatus $gameStatus */
        $gameStatus = $this->em->getRepository(GameStatus::class)->find($gameStatusId);

        if (is_null($gameStatus)) {
            throw new EntityNotFoundException("GameStatus with id: $gameStatusId not found.");
        }

        return $gameStatus;
    }

    /**
     * @return GameStatus[]
     */
    public function findAll(): array
    {
        $gameCollection = $this->em->getRepository(GameStatus::class)->findAll();

        return $gameCollection;
    }
}
