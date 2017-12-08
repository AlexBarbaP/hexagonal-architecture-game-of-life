<?php
declare(strict_types=1);

namespace Application\Factories;

use Application\CommandHandlers\Simulation\InitializeSimulationCommandHandler;
use Application\CommandHandlers\Simulation\IterateSimulationCommandHandler;
use Application\Commands\Simulation\InitializeSimulationCommand;
use Application\Commands\Simulation\IterateSimulationCommand;
use Application\Queries\Simulation\SimulationStatusQuery;
use Application\QueryHandlers\Simulation\SimulationStatusQueryHandler;
use Domain\Model\Ports\GameStatusRepositoryInterface;
use Domain\Model\Ports\GameStatusStoreInterface;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

class CommandBusFactory
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(
        GameStatusRepositoryInterface $gameStatusRepository,
        GameStatusStoreInterface $gameStatusStore
    ) {
        $nameExtractor = new ClassNameExtractor();

        $inflector = new HandleInflector();

        // register commands
        $initializeSimulationCommandHandler = new InitializeSimulationCommandHandler($gameStatusRepository, $gameStatusStore);
        $iterateSimulationCommandHandler    = new IterateSimulationCommandHandler($gameStatusRepository, $gameStatusStore);
        $simulationStatusQueryHandler       = new SimulationStatusQueryHandler($gameStatusRepository, $gameStatusStore);

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
