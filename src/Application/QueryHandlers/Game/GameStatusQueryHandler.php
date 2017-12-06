<?php
declare(strict_types=1);

namespace Application\QueryHandlers\Game;

use Application\Queries\Game\GameStatusQuery;
use Domain\Model\Board;

class GameStatusQueryHandler
{
    /**
     * @param GameStatusQuery $gameStatusQuery
     *
     * @return Board
     */
    public function handle(GameStatusQuery $gameStatusQuery): Board
    {
        $game = $gameStatusQuery->game();

        return $game->getBoard();
    }
}
