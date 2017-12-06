<?php
declare(strict_types=1);

namespace Application\Commands\Game;

class IterateGameCommand
{
    /** @var array */
    private $gameStatus;

    /**
     * @param array $gameStatus
     */
    public function __construct(array $gameStatus)
    {
        $this->gameStatus = $gameStatus;
    }

    /**
     * @return array
     */
    public function gameStatus(): array
    {
        return $this->gameStatus;
    }
}
