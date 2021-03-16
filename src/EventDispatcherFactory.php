<?php

namespace hxv\TaskRofi;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventDispatcherFactory
{
    /**
     * @param iterable<EventSubscriberInterface> $eventSubscribers
     */
    public function __construct(private iterable $eventSubscribers)
    {
    }

    public function create(): EventDispatcherInterface
    {
        $eventDispatcher = new EventDispatcher();

        foreach ($this->eventSubscribers as $eventSubscriber) {
            $eventDispatcher->addSubscriber($eventSubscriber);
        }

        return $eventDispatcher;
    }
}
