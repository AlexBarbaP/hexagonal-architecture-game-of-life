<?php
declare(strict_types=1);

namespace Application\ConsoleCommands;

use Application\Application;
use Application\Config\Config;
use Application\InputParser;
use Application\InputValidator;
use Application\OutputParser;
use Exception;
use Infrastructure\Doctrine\DoctrineEntityManagerFactory;
use Infrastructure\Doctrine\RepositoryInterfaceAdapters\DoctrineGameStatusRepositoryAdapter;
use Infrastructure\Doctrine\StoreInterfaceAdapters\DoctrineGameStatusStoreAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SimulationConsoleCommand extends Command
{
    const COMMAND_NAME = 'simulation:start';

    const ARGUMENT_HEIGHT = 'height';
    const ARGUMENT_WIDTH = 'width';
    const ARGUMENT_MAX_ITERATIONS = 'max_iterations';

    const ITERATIONS_SLEEP_DELAY = 1000;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Executes Simulation of Life simulation.')
            ->setHelp('This command starts Simulation of Life simulation on NxN board based on user input.')
            ->addArgument(self::ARGUMENT_HEIGHT, InputArgument::REQUIRED, 'Board rows number.')
            ->addArgument(self::ARGUMENT_WIDTH, InputArgument::REQUIRED, 'Board columns number.')
            ->addArgument(self::ARGUMENT_MAX_ITERATIONS, InputArgument::OPTIONAL, 'Iterations number limit.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argumentHeight     = $input->getArgument(self::ARGUMENT_HEIGHT);
        $argumentWidth      = $input->getArgument(self::ARGUMENT_WIDTH);
        $argumentIterations = (int)$input->getArgument(self::ARGUMENT_MAX_ITERATIONS);

        $config = Config::getConfig(Config::PROD_ENV);

        $masterDoctrineEntityManagerFactory = new DoctrineEntityManagerFactory(
            $config[Config::ENTITY_PATHS],
            $config[Config::MASTER_DB_PARAMS]
        );
        $masterEntityManager          = $masterDoctrineEntityManagerFactory->getEntityManager();

        $slaveDoctrineEntityManagerFactory = new DoctrineEntityManagerFactory(
            $config[Config::ENTITY_PATHS],
            $config[Config::SLAVE_DB_PARAMS]
        );
        $slaveEntityManager          = $slaveDoctrineEntityManagerFactory->getEntityManager();

        $doctrineGameStatusRepository = new DoctrineGameStatusRepositoryAdapter($slaveEntityManager);
        $doctrineGameStatusStore      = new DoctrineGameStatusStoreAdapter($masterEntityManager);

        $inputParser    = new InputParser();
        $outputParser   = new OutputParser();
        $inputValidator = new InputValidator();

        try {
            $application = new Application(
                $doctrineGameStatusRepository,
                $doctrineGameStatusStore,
                $inputParser,
                $outputParser,
                $inputValidator,
                $argumentIterations ?: 0
            );

            $application->init($argumentHeight, $argumentWidth);

            $iteration = 1;
            do {
                $this->renderBoard($application, $output, $iteration++);

                $application->iterate();

                usleep(self::ITERATIONS_SLEEP_DELAY);
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

    /**
     * @param Application $application
     * @param OutputInterface $output
     * @param $iteration
     */
    private function renderBoard(Application $application, OutputInterface $output, $iteration)
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
