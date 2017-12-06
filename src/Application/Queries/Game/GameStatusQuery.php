<?php
declare(strict_types=1);

namespace Application\Queries\Game;

use Domain\Model\Game;

class GameStatusQuery
{
    /** @var Game */
    private $game;

    /**
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = clone $game;
    }

    /**
     * @return Game
     */
    public function game()
    {
        return clone $this->game;
    }
}
