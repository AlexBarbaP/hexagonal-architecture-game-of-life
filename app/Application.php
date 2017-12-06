<?php
declare(strict_types=1);

namespace Application;

use Application\Commands\Game\InitializeGameCommand;
use Application\Commands\Game\IterateGameCommand;
use Application\Exceptions\InvalidInputException;
use Application\Factories\CommandBusFactory;
use Application\Queries\Game\GameStatusQuery;
use Domain\Model\Board;
use Domain\Model\Game;
use Domain\Model\PopulateStrategies\RandomPopulateStrategy;
use Domain\Model\Size;
use League\Tactician\CommandBus;

class Application
{
    const MAX_ITERATIONS = 100;

    /** @var InputParserInterface */
    private $inputParser;

    /** @var OutputParserInterface */
    private $outputParser;

    /** @var ValidatorInterface */
    private $inputValidator;

    /** @var int */
    private $iterations;

    /** @var CommandBus */
    private $commandBus;

    /** @var string */
    private $output;

    /**
     * @param InputParserInterface  $inputParser
     * @param ValidatorInterface    $inputValidator
     * @param OutputParserInterface $outputParser
     * @param int                   $iterations
     */
    public function __construct(
        InputParserInterface $inputParser,
        OutputParserInterface $outputParser,
        ValidatorInterface $inputValidator,
        int $iterations = 0
    ) {
        $this->inputParser    = $inputParser;
        $this->outputParser   = $outputParser;
        $this->inputValidator = $inputValidator;
        $this->iterations     = $iterations ?: self::MAX_ITERATIONS;

        $this->commandBus = $this->getCommandBus();
    }

    /**
     * @param string $height
     * @param string $width
     *
     * @return array|string
     */
    public function run(string $height, string $width)
    {
        try {
            $input = [
                'height' => $height,
                'width'  => $width,
            ];

            $this->inputValidator->validate($input);

            $size = $this->inputParser->parse($input);

            $this->output .= $this->iterateGame($size);



            $gameStatusQuery = new GameStatusQuery($game);

            /** @var Board $board */
            $board = $this->commandBus->handle($gameStatusQuery);

            $gameBoardGrid = $this->outputParser->parse($board);

            return $gameBoardGrid;
        } catch (InvalidInputException $e) {
            return 'Invalid grid size';
        }
    }

    /**
     * @return CommandBus
     */
    private function getCommandBus(): CommandBus
    {
        $commandBusFactory = new CommandBusFactory();

        $commandBus = $commandBusFactory->create();

        return $commandBus;
    }

    /**
     * @param $size
     *
     * @return Game
     */
    private function initializeGame($size): Game
    {
        $randomPopulateStrategy = new RandomPopulateStrategy();

        $initializeGameCommand = new InitializeGameCommand($size->getHeight(), $size->getWidth(), $randomPopulateStrategy);

        $game = $this->commandBus->handle($initializeGameCommand);

        return $game;
    }

    private function iterateGame(Size $size)
    {
        $game = $this->initializeGame($size);

        for ($n = 0; $n < $this->iterations; $n++) {
            $gameBoardStatus = $game->getBoard()->toArray();

            $iterateGameCommand = new IterateGameCommand($gameBoardStatus);

            $game = $this->commandBus->handle($iterateGameCommand);
        }

    }

    /**
     * @param Game $game
     *
     * @return array
     */
    private function getGameOutput(Game $game)
    {
        $gameStatusQuery = new GameStatusQuery($game);

        /** @var Board $board */
        $board = $this->commandBus->handle($gameStatusQuery);

        $gameBoardGrid = $this->outputParser->parse($board);

        return $gameBoardGrid;
    }
}
