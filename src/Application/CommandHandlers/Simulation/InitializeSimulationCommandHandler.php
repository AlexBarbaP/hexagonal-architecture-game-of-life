<?php
declare(strict_types=1);

namespace Application\CommandHandlers\Simulation;

use Application\Commands\Simulation\InitializeSimulationCommand;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;
use Domain\Model\Ports\SimulationStatusStoreInterface;
use Domain\Model\Simulation;
use Domain\Model\Size;
use League\Event\EmitterInterface;

class InitializeSimulationCommandHandler
{
    /** @var SimulationStatusRepositoryInterface */
    private $simulationStatusRepository;

    /** @var SimulationStatusStoreInterface */
    private $simulationStatusStore;

    /** @var EmitterInterface */
    private $emitter;

    /**
     * @param SimulationStatusRepositoryInterface $simulationStatusRepository
     * @param SimulationStatusStoreInterface      $simulationStatusStore
     * @param EmitterInterface                    $emitter
     */
    public function __construct(
        SimulationStatusRepositoryInterface $simulationStatusRepository,
        SimulationStatusStoreInterface $simulationStatusStore,
        EmitterInterface $emitter
    ) {
        $this->simulationStatusRepository = $simulationStatusRepository;
        $this->simulationStatusStore      = $simulationStatusStore;
        $this->emitter                    = $emitter;
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

        $simulation = new Simulation($size, $populateStrategy, $this->emitter);

        return $simulation;
    }
}
