<?php
declare(strict_types=1);

namespace Application\ConsoleCommands;

use Application\Application;
use Application\InputParser;
use Application\InputValidator;
use Application\OutputParser;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RandomSimulationConsoleCommand extends AbstractSimulationConsoleCommand
{
    const COMMAND_NAME        = 'simulation:random';
    const COMMAND_DESCRIPTION = 'Executes Simulation of Life simulation.';
    const COMMAND_HELP        = 'This command starts Simulation of Life simulation on NxN board based on user input.';

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        parent::configure();

        $this->addArgument(static::ARGUMENT_MAX_ITERATIONS, InputArgument::OPTIONAL, 'Iterations number limit.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $argumentHeight     = $input->getArgument(static::ARGUMENT_HEIGHT);
        $argumentWidth      = $input->getArgument(static::ARGUMENT_WIDTH);
        $argumentIterations = (int)$input->getArgument(static::ARGUMENT_MAX_ITERATIONS);

        $inputParser    = new InputParser();
        $outputParser   = new OutputParser();
        $inputValidator = new InputValidator();

        try {
            $application = new Application(
                $this->gameStatusRepository,
                $this->gameStatusStore,
                $inputParser,
                $outputParser,
                $inputValidator,
                $argumentIterations ?: 0,
                null
            );

            $application->init($argumentHeight, $argumentWidth);

            $iteration = 1;
            do {
                $this->renderBoard($application, $output, $iteration++);

                $application->iterate();

                usleep(static::ITERATIONS_SLEEP_DELAY);
            } while (!$application->isSimulationCompleted());

            $this->renderBoard($application, $output, $iteration);

            $output->writeln([
                'Simulation completed',
            ]);
        } catch (Exception $e) {
            $output->writeln([
                'Hexagonal Architecture Simulation of Life Simulation',
                '==============================================',
                'An ERROR has occurred: ' . $e->getMessage(),
            ]);
        }
    }
}
