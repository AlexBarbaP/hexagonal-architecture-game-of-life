<?php
declare(strict_types=1);

namespace Application\CommandHandlers\Simulation;

use Domain\Model\Commands\Simulation\IterateSimulationCommand;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;
use Domain\Model\Ports\SimulationStatusStoreInterface;
use Domain\Model\Simulation;

class IterateSimulationCommandHandler
{
    /** @var SimulationStatusRepositoryInterface */
    private $simulationStatusRepository;

    /** @var SimulationStatusStoreInterface */
    private $simulationStatusStore;

    /**
     * @param SimulationStatusRepositoryInterface $simulationStatusRepository
     * @param SimulationStatusStoreInterface      $simulationStatusStore
     */
    public function __construct(
        SimulationStatusRepositoryInterface $simulationStatusRepository,
        SimulationStatusStoreInterface $simulationStatusStore
    ) {
        $this->simulationStatusRepository = $simulationStatusRepository;
        $this->simulationStatusStore      = $simulationStatusStore;
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
