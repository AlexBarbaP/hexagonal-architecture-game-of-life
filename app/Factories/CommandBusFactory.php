<?php
declare(strict_types=1);

namespace Application\Factories;

use Application\CommandHandlers\Game\InitializeGameCommandHandler;
use Application\CommandHandlers\Game\IterateGameCommandHandler;
use Application\Commands\Game\InitializeGameCommand;
use Application\Commands\Game\IterateGameCommand;
use Application\Queries\Game\GameStatusQuery;
use Application\QueryHandlers\Game\GameStatusQueryHandler;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

class CommandBusFactory
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct()
    {
        $nameExtractor = new ClassNameExtractor();

        $inflector = new HandleInflector();

        // register commands
        $initializeGameCommandHandler = new InitializeGameCommandHandler();
        $iterateGameCommandHandler    = new IterateGameCommandHandler();
        $gameStatusQueryHandler       = new GameStatusQueryHandler();

        $locator = new InMemoryLocator();
        $locator->addHandler($initializeGameCommandHandler, InitializeGameCommand::class);
        $locator->addHandler($iterateGameCommandHandler, IterateGameCommand::class);
        $locator->addHandler($gameStatusQueryHandler, GameStatusQuery::class);

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
