<?php
declare(strict_types=1);

namespace Application\CommandHandlers\Simulation;

use Application\Commands\Simulation\InitializeSimulationCommand;
use Domain\Model\Ports\GameStatusRepositoryInterface;
use Domain\Model\Ports\GameStatusStoreInterface;
use Domain\Model\Simulation;
use Domain\Model\Size;

class InitializeSimulationCommandHandler
{
    /** @var GameStatusRepositoryInterface */
    private $gameStatusRepository;

    /** @var GameStatusStoreInterface */
    private $gameStatusStore;

    /**
     * @param GameStatusRepositoryInterface $gameStatusRepository
     * @param GameStatusStoreInterface      $gameStatusStore
     */
    public function __construct(
        GameStatusRepositoryInterface $gameStatusRepository,
        GameStatusStoreInterface $gameStatusStore
    ) {
        $this->gameStatusRepository = $gameStatusRepository;
        $this->gameStatusStore      = $gameStatusStore;
    }

    /**
     * @param InitializeSimulationCommand $command
     *
     * @return Simulation
     */
    public function handle(InitializeSimulationCommand $command): Simulation
    {
        $size             = new Size($command->height(), $command->width());
        $populateStrategy = $command->populateStrategy();

        $simulation = new Simulation($size, $populateStrategy, $this->gameStatusStore);

        return $simulation;
    }
}
