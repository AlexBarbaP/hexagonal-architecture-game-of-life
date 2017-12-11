<?php
declare(strict_types=1);

namespace Application\EventListeners\Simulation;

use Domain\Model\Entities\SimulationStatus;
use Domain\Model\Entities\SimulationStatusId;
use Domain\Model\Events\SimulationInitializedEvent;
use Domain\Model\Ports\SimulationStatusStoreInterface;
use League\Event\EventInterface;
use League\Event\ListenerInterface;

class SimulationInitializedEventListener implements ListenerInterface
{
    /** @var SimulationStatusStoreInterface */
    private $simulationStatusStore;

    /**
     * @param SimulationStatusStoreInterface $simulationStatusStore
     */
    public function __construct(SimulationStatusStoreInterface $simulationStatusStore)
    {
        $this->simulationStatusStore = $simulationStatusStore;
    }

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /** @var SimulationInitializedEvent $event */
        $board = $event->getBoard();

        $simulationStatusId = SimulationStatusId::create();
        $status             = serialize($board->toArray());

        $simulationStatus = new SimulationStatus($simulationStatusId, $status);

        $this->simulationStatusStore->save($simulationStatus);
    }

    /**
     * @inheritdoc
     */
    public function isListener($listener)
    {
        return $listener === $this;
    }
}
