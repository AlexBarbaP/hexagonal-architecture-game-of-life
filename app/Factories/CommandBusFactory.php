<?php
declare(strict_types=1);

namespace Application\Factories;

use Application\CommandHandlers\Simulation\InitializeSimulationCommandHandler;
use Application\CommandHandlers\Simulation\IterateSimulationCommandHandler;
use Application\QueryHandlers\Simulation\SimulationStatusQueryHandler;
use Domain\Model\Commands\Simulation\InitializeSimulationCommand;
use Domain\Model\Commands\Simulation\IterateSimulationCommand;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;
use Domain\Model\Ports\SimulationStatusStoreInterface;
use Domain\Model\Queries\Simulation\SimulationStatusQuery;
use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

class CommandBusFactory
{
    /** @var CommandBus */
    private $commandBus;

    /** @var EmitterInterface */
    private $eventBus;

    /**
     * @param SimulationStatusRepositoryInterface $simulationStatusRepository
     * @param SimulationStatusStoreInterface      $simulationStatusStore
     * @param EmitterInterface                    $eventBus
     */
    public function __construct(
        SimulationStatusRepositoryInterface $simulationStatusRepository,
        SimulationStatusStoreInterface $simulationStatusStore,
        EmitterInterface $eventBus
    ) {
        $this->eventBus = $eventBus;

        $nameExtractor = new ClassNameExtractor();

        $inflector = new HandleInflector();

        // register commands
        $initializeSimulationCommandHandler = new InitializeSimulationCommandHandler($simulationStatusRepository, $simulationStatusStore, $this->eventBus);
        $iterateSimulationCommandHandler    = new IterateSimulationCommandHandler($simulationStatusRepository, $simulationStatusStore);
        $simulationStatusQueryHandler       = new SimulationStatusQueryHandler($simulationStatusRepository, $simulationStatusStore);

        $locator = new InMemoryLocator();
        $locator->addHandler($initializeSimulationCommandHandler, InitializeSimulationCommand::class);
        $locator->addHandler($iterateSimulationCommandHandler, IterateSimulationCommand::class);
        $locator->addHandler($simulationStatusQueryHandler, SimulationStatusQuery::class);

        $commandHandlerMiddleware = new CommandHandlerMiddleware($nameExtractor, $locator, $inflector);

        $this->commandBus = new CommandBus([$commandHandlerMiddleware]);
    }

    /**
     * @return CommandBus
     */
    public function create()
    {
        return $this->commandBus;
    }
}
