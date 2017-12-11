<?php
declare(strict_types=1);

namespace Domain\Model\Events;

use League\Event\EmitterInterface;
use League\Event\EventInterface;

abstract class EventAbstract implements EventInterface
{
    /** @var string */
    private $id = '';

    /** @var bool */
    private $propagationActive = true;

    /** @var EmitterInterface */
    private $emitter;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param EmitterInterface $emitter
     *
     * @return $this
     */
    public function setEmitter(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;

        return $this;
    }

    /**
     * Get the Emitter.
     *
     * @return EmitterInterface
     */
    public function getEmitter()
    {
        return $this->emitter;
    }

    /**
     * Stop event propagation.
     *
     * @return $this
     */
    public function stopPropagation()
    {
        $this->propagationActive = false;

        return $this;
    }

    /**
     * Check whether propagation was stopped.
     *
     * @return bool
     */
    public function isPropagationStopped()
    {
        return !$this->propagationActive;
    }

    /**
     * Get the event name.
     *
     * @return string
     */
    abstract public function getName();
}
