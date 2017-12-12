<?php
declare(strict_types=1);

namespace Application\ConsoleCommands;

use Application\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractSimulationConsoleCommand extends Command
{
    const ARGUMENT_HEIGHT = 'height';
    const ARGUMENT_WIDTH = 'width';
    const ARGUMENT_MAX_ITERATIONS = 'max_iterations';

    const ITERATIONS_SLEEP_DELAY = 10000;

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp(static::COMMAND_HELP)
            ->addArgument(self::ARGUMENT_HEIGHT, InputArgument::REQUIRED, 'Board rows number.')
            ->addArgument(self::ARGUMENT_WIDTH, InputArgument::REQUIRED, 'Board columns number.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
    }

    /**
     * @param Application $application
     * @param OutputInterface $output
     * @param int $iteration
     */
    protected function renderBoard(Application $application, OutputInterface $output, int $iteration)
    {
        system('clear');

        $boardStatus = $application->getBoardStatus();

        $output->writeln([
            'Hexagonal Architecture Simulation of Life Simulation',
            'Iteration: ' . $iteration,
            '====================================================',
            $boardStatus,
        ]);
    }
}
