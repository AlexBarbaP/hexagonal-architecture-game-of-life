<?php
declare(strict_types=1);

namespace Infrastructure\InMemory\EmitterAdapters;

use League\Event\EmitterInterface;
use League\Event\GeneratorInterface;
use League\Event\ListenerProviderInterface;

class MockEmitterAdapter implements EmitterInterface
{
    public function addListener($event, $listener, $priority = self::P_NORMAL)
    {
        // TODO: Implement addListener() method.
    }

    public function addOneTimeListener($event, $listener, $priority = self::P_NORMAL)
    {
        // TODO: Implement addOneTimeListener() method.
    }

    public function emit($event)
    {
        // TODO: Implement emit() method.
    }

    public function emitBatch(array $events)
    {
        // TODO: Implement emitBatch() method.
    }

    public function emitGeneratedEvents(GeneratorInterface $generator)
    {
        // TODO: Implement emitGeneratedEvents() method.
    }

    public function getListeners($event)
    {
        // TODO: Implement getListeners() method.
    }

    public function hasListeners($event)
    {
        // TODO: Implement hasListeners() method.
    }

    public function removeAllListeners($event)
    {
        // TODO: Implement removeAllListeners() method.
    }

    public function removeListener($event, $listener)
    {
        // TODO: Implement removeListener() method.
    }

    public function useListenerProvider(ListenerProviderInterface $provider)
    {
        // TODO: Implement useListenerProvider() method.
    }
}
