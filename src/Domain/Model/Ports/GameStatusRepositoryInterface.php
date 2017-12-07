<?php
declare(strict_types=1);

namespace Domain\Model\Ports;

use Domain\Exception\EntityNotFoundException;
use Domain\Model\Entities\GameStatus;
use Domain\Model\Entities\GameStatusId;

interface GameStatusRepositoryInterface
{
    /**
     * @param GameStatusId $gameStatusId
     * @return GameStatus
     *
     * @throws EntityNotFoundException
     */
    public function find(GameStatusId $gameStatusId): GameStatus;
}
