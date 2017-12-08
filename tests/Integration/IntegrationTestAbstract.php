<?php
declare(strict_types=1);

namespace Tests\Integration;

use Application\Config\Config;
use Application\Factories\CommandBusFactory;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Infrastructure\Doctrine\DoctrineEntityManagerFactory;
use Infrastructure\Doctrine\RepositoryInterfaceAdapters\DoctrineGameStatusRepositoryAdapter;
use Infrastructure\Doctrine\StoreInterfaceAdapters\DoctrineGameStatusStoreAdapter;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Fixtures\DoctrineGameStatusFixtureLoader;

class IntegrationTestAbstract extends TestCase
{
    /** @var string */
    protected $environment = Config::TEST_ENV;

    /** @var EntityManager */
    protected $masterEntityManager;

    /** @var EntityManager */
    protected $slaveEntityManager;

    /** @var DoctrineGameStatusRepositoryAdapter */
    protected $doctrineGameStatusRepository;

    /** @var DoctrineGameStatusStoreAdapter */
    protected $doctrineGameStatusStore;

    /** @var CommandBus */
    protected $commandBus;

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

        $this->doctrineGameStatusRepository = new DoctrineGameStatusRepositoryAdapter($this->slaveEntityManager);
        $this->doctrineGameStatusStore      = new DoctrineGameStatusStoreAdapter($this->masterEntityManager);

        $this->fixturesLoader();

        $this->setupCommandBus();
    }

    /**
     *
     */
    private function fixturesLoader(): void
    {
        $loader = new Loader();
        $loader->addFixture(new DoctrineGameStatusFixtureLoader());

        $purger   = new ORMPurger();
        $executor = new ORMExecutor($this->masterEntityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     *
     */
    private function setupCommandBus()
    {
        $commandBusFactory = new CommandBusFactory(
            $this->doctrineGameStatusRepository,
            $this->doctrineGameStatusStore
        );

        $this->commandBus = $commandBusFactory->create();
    }
}
