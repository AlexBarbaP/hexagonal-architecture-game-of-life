<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\Model\Commands\Simulation\InitializeSimulationCommand;
use App\Domain\Model\Commands\Simulation\IterateSimulationCommand;
use App\Domain\Model\PopulateStrategies\FixedPopulateStrategy;
use App\Domain\Model\PopulateStrategies\RandomPopulateStrategy;
use App\Domain\Model\Ports\SimulationStatusRepositoryInterface;
use App\Domain\Model\Ports\SimulationStatusStoreInterface;
use App\Domain\Model\Simulation;
use App\Infrastructure\Doctrine\DoctrineEntityManagerFactory;
use App\Infrastructure\Doctrine\RepositoryInterfaceAdapters\DoctrineSimulationStatusRepositoryAdapter;
use App\Infrastructure\Doctrine\StoreInterfaceAdapters\DoctrineSimulationStatusStoreAdapter;
use Application\Config\Config;
use Application\Factories\CommandBusFactory;
use Application\Factories\EventBusFactory;
use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "iterate/{height}/{width}/{iterations}",
 *     name="iterate-simulation",
 *     requirements={"height"="\d+", "width"="\d+", "iterations"="\d+"}
 * )
 */
class IterateSimulationController extends AbstractController
{
    /** @var Config */
    protected $config;

    /** @var SimulationStatusRepositoryInterface */
    protected $simulationStatusRepository;

    /** @var SimulationStatusStoreInterface */
    protected $simulationStatusStore;

    /** @var CommandBus */
    private $commandBus;

    /** @var EmitterInterface */
    private $eventBus;

    public function __invoke(int $height, int $width, int $iterations, SessionInterface $session)
    {
        $this->getConfig();
        $this->getEntityManagers();
        $this->getBuses();

        if (!$session->has('simulationStatus') || !$session->get('iterations')) {
            $populateStrategy = new RandomPopulateStrategy();

            $command = new InitializeSimulationCommand($height, $width, $populateStrategy);
        } else {
            $simulationStatus = $session->get('simulationStatus');

            $populateStrategy = new FixedPopulateStrategy($simulationStatus);

            $command = new InitializeSimulationCommand($height, $width, $populateStrategy);

            /** @var Simulation $simulation */
            $simulation = $this->commandBus->handle($command);

            $command = new IterateSimulationCommand($simulation);
        }

        /** @var Simulation $simulation */
        $simulation = $this->commandBus->handle($command);

        $simulationStatus = $simulation->getBoard()->toArray();
        $session->set('simulationStatus', $simulationStatus);

        return $this->render('render.html.twig', [
            'grid' => $simulation->getBoard()->toArray(),
            'iterate' => $this->getIterate($session, $iterations),
        ]);
    }

    private function getIterate(SessionInterface $session, int $iterations): int
    {
        if (!$session->has('iterations') || $session->get('iterations') === 0) {
            $session->set('iterations', --$iterations);
            return 1;
        }

        $iterations = $session->get('iterations');

        if ($iterations > 0) {
            $session->set('iterations', --$iterations);
        }

        return $iterations;
    }

    private function getConfig(): void
    {
        $this->config = Config::getConfig(Config::PROD_ENV);
    }

    private function getEntityManagers(): void
    {
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

    private function getBuses(): void
    {
        $eventBusFactory = new EventBusFactory($this->simulationStatusStore);
        $this->eventBus  = $eventBusFactory->create();

        $commandBusFactory = new CommandBusFactory(
            $this->simulationStatusRepository,
            $this->simulationStatusStore,
            $this->eventBus
        );
        $this->commandBus  = $commandBusFactory->create();
    }
}
