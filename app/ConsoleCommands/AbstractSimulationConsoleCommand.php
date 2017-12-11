<?php
declare(strict_types=1);

namespace Application\ConsoleCommands;

use Application\Application;
use Application\Config\Config;
use Domain\Model\Ports\SimulationStatusRepositoryInterface;
use Domain\Model\Ports\SimulationStatusStoreInterface;
use Infrastructure\Doctrine\DoctrineEntityManagerFactory;
use Infrastructure\Doctrine\RepositoryInterfaceAdapters\DoctrineSimulationStatusRepositoryAdapter;
use Infrastructure\Doctrine\StoreInterfaceAdapters\DoctrineSimulationStatusStoreAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractSimulationConsoleCommand extends Command
{
    const ARGUMENT_HEIGHT         = 'height';
    const ARGUMENT_WIDTH          = 'width';
    const ARGUMENT_MAX_ITERATIONS = 'max_iterations';

    const ITERATIONS_SLEEP_DELAY = 10000;

    /** @var Config */
    protected $config;

    /** @var SimulationStatusRepositoryInterface */
    protected $simulationStatusRepository;

    /** @var SimulationStatusStoreInterface */
    protected $simulationStatusStore;

    /**
     * @param string|null $name
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->config = Config::getConfig(Config::PROD_ENV);

        $masterDoctrineEntityManagerFactory = new DoctrineEntityManagerFactory(
            $this->config[Config::ENTITY_PATHS],
            $this->config[Config::MASTER_DB_PARAMS]
        );
        $masterEntityManager                = $masterDoctrineEntityManagerFactory->getEntityManager();

        $this->simulationStatusStore = new DoctrineSimulationStatusStoreAdapter($masterEntityManager);

        $slaveDoctrineEntityManagerFactory = new DoctrineEntityManagerFactory(
            $this->config[Config::ENTITY_PATHS],
            $this->config[Config::SLAVE_DB_PARAMS]
        );
        $slaveEntityManager                = $slaveDoctrineEntityManagerFactory->getEntityManager();

        $this->simulationStatusRepository = new DoctrineSimulationStatusRepositoryAdapter($slaveEntityManager);
    }

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
     * @param Application     $application
     * @param OutputInterface $output
     * @param int             $iteration
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
