<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Menu\TopItem;
use hxv\TaskRofi\Taskwarrior\TaskItem;
use hxv\TaskRofi\Taskwarrior\Taskwarrior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TaskwarriorMenuCreatedListener implements EventSubscriberInterface
{
    public function __construct(private Taskwarrior $taskwarrior)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            ItemSelectedEvent::class => 'onMenuCreated',
        ];
    }

    public function onMenuCreated(ItemSelectedEvent $event): void
    {
        if ($event->stack()->current()->getRole() !== TopItem::ROLE || $event->stack()->current()->getLabel() !== Role::MENU) {
            return;
        }

        foreach ($this->taskwarrior->getTasks() as $task) {
            $event->addMenuItem(new TaskItem($task));
        }

        // TODO - copy UUID
        // TODO - obsługa priorytetów
        // TODO - obsługa timew - łączenie czasów, zmiana czasu, wydłużanie, skracanie
    }
}
