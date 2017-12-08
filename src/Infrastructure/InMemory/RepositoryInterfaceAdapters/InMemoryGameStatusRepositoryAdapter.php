<?php
declare(strict_types=1);

namespace Infrastructure\InMemory\RepositoryInterfaceAdapters;

use Domain\Exception\EntityNotFoundException;
use Domain\Model\Entities\GameStatus;
use Domain\Model\Entities\GameStatusId;
use Domain\Model\Ports\GameStatusRepositoryInterface;

class InMemoryGameStatusRepositoryAdapter implements GameStatusRepositoryInterface
{
    /** @var GameStatus[] */
    private $inMemoryData = [];

    /**
     * @param GameStatus[] $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $gameStatusItem) {
            $this->inMemoryData[$gameStatusItem->getId()->id()] = $gameStatusItem;
        }
    }

    /**
     * @param GameStatusId $gameStatusId
     * @return GameStatus
     *
     * @throws EntityNotFoundException
     */
    public function find(GameStatusId $gameStatusId): GameStatus
    {
        $gameStatusIdString = $gameStatusId->id();

        if (!array_key_exists($gameStatusIdString, $this->inMemoryData)) {
            throw new EntityNotFoundException("GameStatus with id: $gameStatusIdString not found.");
        }

        return $this->inMemoryData[$gameStatusIdString];
    }

    /**
     * @return GameStatus[]
     */
    public function findAll(): array
    {
        return $this->inMemoryData;
    }
}
