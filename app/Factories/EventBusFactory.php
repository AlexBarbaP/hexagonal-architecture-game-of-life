<?php
declare(strict_types=1);

namespace Application\Factories;

use App\Application\EventListeners\Simulation\SimulationInitializedEventListener;
use App\Domain\Model\Ports\SimulationStatusStoreInterface;
use League\Event\Emitter;
use League\Event\EmitterInterface;

class EventBusFactory
{
    /** @var EmitterInterface */
    private $emitter;

    /**
     * @param SimulationStatusStoreInterface $simulationStatusStore
     */
    public function __construct(SimulationStatusStoreInterface $simulationStatusStore)
    {
        $this->emitter = new Emitter();

        $this->emitter->addListener('Event.Simulation.Initialized', new SimulationInitializedEventListener($simulationStatusStore));
    }

    /**
     * @return EmitterInterface
     */
    public function create()
    {
        return $this->emitter;
    }
}
