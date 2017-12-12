<?php
declare(strict_types=1);

namespace Application;

use Application\Exceptions\InvalidInputException;
use Domain\Model\Board;
use Domain\Model\PopulateStrategies\RandomPopulateStrategy;
use Domain\Model\Simulation;
use Domain\Model\Size;
use PHPUnit\Runner\Exception;

class Application
{
    /** @var InputParserInterface */
    private $inputParser;

    /** @var OutputParserInterface */
    private $outputParser;

    /** @var ValidatorInterface */
    private $inputValidator;

    /** @var int */
    private $iterations;

    /** @var int */
    private $currentIteration = 0;

    /** @var Simulation */
    private $simulation;

    /**
     * @param InputParserInterface $inputParser
     * @param OutputParserInterface $outputParser
     * @param ValidatorInterface $inputValidator
     * @param int $iterations
     */
    public function __construct(
        InputParserInterface $inputParser,
        OutputParserInterface $outputParser,
        ValidatorInterface $inputValidator,
        int $iterations
    ) {
        $this->inputParser    = $inputParser;
        $this->outputParser   = $outputParser;
        $this->inputValidator = $inputValidator;
        $this->iterations     = $iterations;
    }

    /**
     * @param string $height
     * @param string $width
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
     *
     * @return Simulation
     */
    private function initializeSimulation(Size $size): Simulation
    {
        $populateStrategy = new RandomPopulateStrategy();

        $simulation = new Simulation($size, $populateStrategy);

        return $simulation;
    }

    /**
     *
     */
    public function iterate(): void
    {
        $this->simulation->iterate();

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
        $board = $this->simulation->getBoard();

        $simulationBoardGrid = $this->outputParser->parse($board);

        return $simulationBoardGrid;
    }
}
