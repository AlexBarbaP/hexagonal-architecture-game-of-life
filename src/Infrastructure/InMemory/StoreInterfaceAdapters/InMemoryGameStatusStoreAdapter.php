<?php
declare(strict_types=1);

namespace Infrastructure\InMemory\StoreInterfaceAdapters;

use Domain\Model\Entities\GameStatus;
use Domain\Model\Ports\GameStatusStoreInterface;

class InMemoryGameStatusStoreAdapter implements GameStatusStoreInterface
{
    /** @var GameStatus[] */
    private static $inMemoryData = [];

    /**
     * @param GameStatus $gameStatus
     */
    public function save(GameStatus $gameStatus): void
    {
        $gameStatusIdString = $gameStatus->getId()->id();

        self::$inMemoryData[$gameStatusIdString] = $gameStatus;
    }
}
