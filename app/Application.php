<?php
declare(strict_types=1);

namespace Application;

use Application\Commands\Simulation\InitializeSimulationCommand;
use Application\Commands\Simulation\IterateSimulationCommand;
use Application\Exceptions\InvalidInputException;
use Application\Factories\CommandBusFactory;
use Application\Queries\Simulation\SimulationStatusQuery;
use Domain\Exception\EntityNotFoundException;
use Domain\Model\Board;
use Domain\Model\Entities\GameStatusId;
use Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use Domain\Model\PopulateStrategies\RandomPopulateStrategy;
use Domain\Model\Ports\GameStatusRepositoryInterface;
use Domain\Model\Ports\GameStatusStoreInterface;
use Domain\Model\Simulation;
use Domain\Model\Size;
use League\Tactician\CommandBus;
use PHPUnit\Runner\Exception;

class Application
{
    /** @var GameStatusRepositoryInterface */
    private $gameStatusRepository;

    /** @var GameStatusStoreInterface */
    private $gameStatusStore;

    /** @var InputParserInterface */
    private $inputParser;

    /** @var OutputParserInterface */
    private $outputParser;

    /** @var ValidatorInterface */
    private $inputValidator;

    /** @var int */
    private $iterations;

    /** @var GameStatusId */
    private $gameStatusId;

    /** @var int */
    private $currentIteration = 0;

    /** @var CommandBus */
    private $commandBus;

    /** @var Simulation */
    private $simulation;

    /**
     * @param GameStatusRepositoryInterface $gameStatusRepository
     * @param GameStatusStoreInterface      $gameStatusStore
     * @param InputParserInterface          $inputParser
     * @param OutputParserInterface         $outputParser
     * @param ValidatorInterface            $inputValidator
     * @param int                           $iterations
     * @param GameStatusId                  $gameStatusId
     */
    public function __construct(
        GameStatusRepositoryInterface $gameStatusRepository,
        GameStatusStoreInterface $gameStatusStore,
        InputParserInterface $inputParser,
        OutputParserInterface $outputParser,
        ValidatorInterface $inputValidator,
        int $iterations,
        GameStatusId $gameStatusId = null
    ) {
        $this->gameStatusRepository = $gameStatusRepository;
        $this->gameStatusStore      = $gameStatusStore;
        $this->inputParser          = $inputParser;
        $this->outputParser         = $outputParser;
        $this->inputValidator       = $inputValidator;
        $this->iterations           = $iterations;
        $this->gameStatusId         = $gameStatusId;

        $this->commandBus = $this->getCommandBus();
    }

    /**
     * @param string $height
     * @param string $width
     *
     * @throws EntityNotFoundException
     */
    public function init(string $height, string $width): void
    {
        try {
            $input = [
                'height' => $height,
                'width'  => $width,
            ];

            $this->inputValidator->validate($input);

            $size = $this->inputParser->parse($input);

            $this->simulation = $this->initializeSimulation($size);
        } catch (InvalidInputException $e) {
            throw new Exception('Invalid input exception.');
        }
    }

    /**
     *
     */
    public function iterate(): void
    {
        $iterateSimulationCommand = new IterateSimulationCommand($this->simulation);

        $this->simulation = $this->commandBus->handle($iterateSimulationCommand);

        $this->currentIteration++;
    }

    /**
     * @return bool
     */
    public function isSimulationCompleted(): bool
    {
        if ($this->simulation->isCompleted()) {
            return true;
        }

        $allIterationsCompleted = $this->currentIteration > $this->iterations - 1;

        if ($this->iterations && $allIterationsCompleted) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getBoardStatus(): string
    {
        $simulationStatusQuery = new SimulationStatusQuery($this->simulation);

        /** @var Board $board */
        $board = $this->commandBus->handle($simulationStatusQuery);

        $simulationBoardGrid = $this->outputParser->parse($board);

        return $simulationBoardGrid;
    }

    /**
     * @return CommandBus
     */
    private function getCommandBus(): CommandBus
    {
        $commandBusFactory = new CommandBusFactory($this->gameStatusRepository, $this->gameStatusStore);

        $commandBus = $commandBusFactory->create();

        return $commandBus;
    }

    /**
     * @param Size $size
     * @return Simulation
     *
     * @throws EntityNotFoundException
     */
    private function initializeSimulation(Size $size): Simulation
    {
        if (is_null($this->gameStatusId)) {
            $populateStrategy = new RandomPopulateStrategy();
        } else {
            $gridStatus      = $this->gameStatusRepository->find($this->gameStatusId);
            $gridStatusArray = unserialize($gridStatus->getStatus());

            $populateStrategy = new FixedPopulateStrategy($gridStatusArray);
        }

        $initializeSimulationCommand = new InitializeSimulationCommand($size->getHeight(), $size->getWidth(), $populateStrategy);

        $simulation = $this->commandBus->handle($initializeSimulationCommand);

        return $simulation;
    }
}
