<?php
declare(strict_types=1);

namespace Tests\Integration;

use Application\Config\Config;
use Application\Factories\CommandBusFactory;
use Application\Factories\EventBusFactory;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Infrastructure\Doctrine\DoctrineEntityManagerFactory;
use Infrastructure\Doctrine\RepositoryInterfaceAdapters\DoctrineSimulationStatusRepositoryAdapter;
use Infrastructure\Doctrine\StoreInterfaceAdapters\DoctrineSimulationStatusStoreAdapter;
use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Fixtures\DoctrineSimulationStatusFixtureLoader;

class IntegrationTestAbstract extends TestCase
{
    /** @var string */
    protected $environment = Config::TEST_ENV;

    /** @var EntityManager */
    protected $masterEntityManager;

    /** @var EntityManager */
    protected $slaveEntityManager;

    /** @var DoctrineSimulationStatusRepositoryAdapter */
    protected $doctrineSimulationStatusRepository;

    /** @var DoctrineSimulationStatusStoreAdapter */
    protected $doctrineSimulationStatusStore;

    /** @var CommandBus */
    protected $commandBus;

    /** @var EmitterInterface */
    protected $eventBus;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $config = Config::getConfig($this->environment);

        $masterDoctrineEntityManagerFactory = new DoctrineEntityManagerFactory(
            $config[Config::ENTITY_PATHS],
            $config[Config::MASTER_DB_PARAMS]
        );
        $this->masterEntityManager          = $masterDoctrineEntityManagerFactory->getEntityManager();

        $slaveDoctrineEntityManagerFactory = new DoctrineEntityManagerFactory(
            $config[Config::ENTITY_PATHS],
            $config[Config::SLAVE_DB_PARAMS]
        );
        $this->slaveEntityManager          = $slaveDoctrineEntityManagerFactory->getEntityManager();

        $this->doctrineSimulationStatusRepository = new DoctrineSimulationStatusRepositoryAdapter($this->slaveEntityManager);
        $this->doctrineSimulationStatusStore      = new DoctrineSimulationStatusStoreAdapter($this->masterEntityManager);

        $this->fixturesLoader();

        $this->setupEventBus();

        $this->setupCommandBus();
    }

    /**
     *
     */
    private function fixturesLoader(): void
    {
        $loader = new Loader();
        $loader->addFixture(new DoctrineSimulationStatusFixtureLoader());

        $purger   = new ORMPurger();
        $executor = new ORMExecutor($this->masterEntityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     *
     */
    private function setupEventBus(): void
    {
        $eventBusFactory = new EventBusFactory($this->doctrineSimulationStatusStore);

        $this->eventBus = $eventBusFactory->create();
    }

    /**
     *
     */
    private function setupCommandBus(): void
    {
        $commandBusFactory = new CommandBusFactory(
            $this->doctrineSimulationStatusRepository,
            $this->doctrineSimulationStatusStore,
            $this->eventBus
        );

        $this->commandBus = $commandBusFactory->create();
    }
}
