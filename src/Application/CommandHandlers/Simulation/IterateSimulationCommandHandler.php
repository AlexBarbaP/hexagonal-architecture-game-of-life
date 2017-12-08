<?php
declare(strict_types=1);

namespace Application\CommandHandlers\Simulation;

use Application\Commands\Simulation\IterateSimulationCommand;
use Domain\Model\Ports\GameStatusRepositoryInterface;
use Domain\Model\Ports\GameStatusStoreInterface;
use Domain\Model\Simulation;

class IterateSimulationCommandHandler
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
     * @param IterateSimulationCommand $command
     *
     * @return Simulation
     */
    public function handle(IterateSimulationCommand $command): Simulation
    {
        $simulation = $command->simulation();

        $simulation->iterate();

        return $simulation;
    }
}
