<?php
declare(strict_types=1);

namespace App\Application\CommandHandlers\Simulation;

use App\Domain\Model\Commands\Simulation\IterateSimulationCommand;
use App\Domain\Model\Ports\SimulationStatusRepositoryInterface;
use App\Domain\Model\Ports\SimulationStatusStoreInterface;
use App\Domain\Model\Simulation;

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
