<?php
declare(strict_types=1);

namespace Domain\Model\Ports;

use Domain\Model\Entities\GameStatus;

interface GameStatusStoreInterface
{
    /**
     * @param GameStatus $gameStatus
     */
    public function save(GameStatus $gameStatus): void;
}
