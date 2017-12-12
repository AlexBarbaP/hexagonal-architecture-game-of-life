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
use Domain\Model\Entities\SimulationStatusId;
use Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use Domain\Model\PopulateStrategies\RandomPopulateStrategy;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;
use Domain\Model\Ports\SimulationStatusStoreInterface;
use Domain\Model\Simulation;
use Domain\Model\Size;
use League\Tactician\CommandBus;
use PHPUnit\Runner\Exception;

class Application
{
    /** @var SimulationStatusRepositoryInterface */
    private $simulationStatusRepository;

    /** @var SimulationStatusStoreInterface */
    private $simulationStatusStore;

    /** @var InputParserInterface */
    private $inputParser;

    /** @var OutputParserInterface */
    private $outputParser;

    /** @var ValidatorInterface */
    private $inputValidator;

    /** @var int */
    private $iterations;

    /** @var SimulationStatusId */
    private $simulationStatusId;

    /** @var int */
    private $currentIteration = 0;

    /** @var CommandBus */
    private $commandBus;

    /** @var Simulation */
    private $simulation;

    /**
     * @param SimulationStatusRepositoryInterface $simulationStatusRepository
     * @param SimulationStatusStoreInterface $simulationStatusStore
     * @param InputParserInterface $inputParser
     * @param OutputParserInterface $outputParser
     * @param ValidatorInterface $inputValidator
     * @param int $iterations
     * @param SimulationStatusId $simulationStatusId
     */
    public function __construct(
        SimulationStatusRepositoryInterface $simulationStatusRepository,
        SimulationStatusStoreInterface $simulationStatusStore,
        InputParserInterface $inputParser,
        OutputParserInterface $outputParser,
        ValidatorInterface $inputValidator,
        int $iterations,
        SimulationStatusId $simulationStatusId = null
    ) {
        $this->simulationStatusRepository = $simulationStatusRepository;
        $this->simulationStatusStore      = $simulationStatusStore;
        $this->inputParser                = $inputParser;
        $this->outputParser               = $outputParser;
        $this->inputValidator             = $inputValidator;
        $this->iterations                 = $iterations;
        $this->simulationStatusId         = $simulationStatusId;

        $this->commandBus = $this->getCommandBus();
    }

    /**
     * @return CommandBus
     */
    private function getCommandBus(): CommandBus
    {
        $commandBusFactory = new CommandBusFactory(
            $this->simulationStatusRepository,
            $this->simulationStatusStore
        );

        $commandBus = $commandBusFactory->create();

        return $commandBus;
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
                'width' => $width,
            ];

            $this->inputValidator->validate($input);

            $size = $this->inputParser->parse($input);

            $this->simulation = $this->initializeSimulation($size);
        } catch (InvalidInputException $e) {
            throw new Exception('Invalid input exception.');
        }
    }

    /**
     * @param Size $size
     * @return Simulation
     *
     * @throws EntityNotFoundException
     */
    private function initializeSimulation(Size $size): Simulation
    {
        if (is_null($this->simulationStatusId)) {
            $populateStrategy = new RandomPopulateStrategy();
        } else {
            $gridStatus      = $this->simulationStatusRepository->find($this->simulationStatusId);
            $gridStatusArray = unserialize($gridStatus->getStatus());

            $populateStrategy = new FixedPopulateStrategy($gridStatusArray);
        }

        $initializeSimulationCommand = new InitializeSimulationCommand($size->getHeight(), $size->getWidth(), $populateStrategy);

        $simulation = $this->commandBus->handle($initializeSimulationCommand);

        return $simulation;
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
}
