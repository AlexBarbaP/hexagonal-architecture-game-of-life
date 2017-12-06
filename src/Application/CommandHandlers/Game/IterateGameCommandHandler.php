<?php
declare(strict_types=1);

namespace Application\CommandHandlers\Game;

use Application\Commands\Game\IterateGameCommand;
use Domain\Model\Game;
use Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use Domain\Model\Size;

class IterateGameCommandHandler
{
    /**
     * @param IterateGameCommand $command
     *
     * @return array
     */
    public function handle(IterateGameCommand $command): array
    {
        $gameStatus = $command->gameStatus();

        $height = count($gameStatus);
        $width  = count($gameStatus[0]);

        $size                  = new Size($height, $width);
        $fixedPopulateStrategy = new FixedPopulateStrategy($gameStatus);

        $game = new Game($size, $fixedPopulateStrategy);
        $game->iterate();

        return $game->getBoard()->toArray();
    }
}
