<?php
declare(strict_types=1);

namespace Application\CommandHandlers\Game;

use Application\Commands\Game\InitializeGameCommand;
use Domain\Model\Game;
use Domain\Model\Size;

class InitializeGameCommandHandler
{
    /**
     * @param InitializeGameCommand $command
     *
     * @return Game
     */
    public function handle(InitializeGameCommand $command): Game
    {
        $size             = new Size($command->height(), $command->width());
        $populateStrategy = $command->populateStrategy();

        $game = new Game($size, $populateStrategy);

        return $game;
    }
}
