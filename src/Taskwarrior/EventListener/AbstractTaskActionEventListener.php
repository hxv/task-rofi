<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Event\ItemStack;
use hxv\TaskRofi\Menu\ItemInterface;
use hxv\TaskRofi\Taskwarrior\TaskItem;
use hxv\TaskRofi\Taskwarrior\Task;
use hxv\TaskRofi\Taskwarrior\Taskwarrior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractTaskActionEventListener implements EventSubscriberInterface
{
    public function __construct(protected Taskwarrior $taskwarrior)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            ItemSelectedEvent::class => [
                ['onItemSelected', static::getPriority()],
                ['onMenuCreated', static::getPriority()],
            ],
        ];
    }

    protected static function getPriority(): int
    {
        return 0;
    }

    public function onItemSelected(ItemSelectedEvent $event): void
    {
        $currentItem = $event->stack()->current();

        $taskItem = $event->stack()->get(1);
        if (!$taskItem instanceof TaskItem) {
            return;
        }

        $task = $taskItem->getTask();

        $this->handleTaskAction($task, $currentItem->getRole());

        $taskItem->setTask($this->taskwarrior->getTask($task->uuid()));
    }

    public function onMenuCreated(ItemSelectedEvent $event): void
    {
        $currentItem = $event->stack()->current();
        if (!$currentItem instanceof TaskItem) {
            return;
        }

        $task = $currentItem->getTask();

        if (!$this->taskMatches($task)) {
            return;
        }

        array_map([$event, 'addMenuItem'], $this->getTaskActions($task));
    }

    abstract protected function taskMatches(Task $task): bool;

    /**
     * @param Task $task
     *
     * @return ItemInterface[]
     *
     * @TODO - dodać możliwość zwracania pojedynczych elementów/nulla
     */
    abstract protected function getTaskActions(Task $task): array;

    abstract protected function handleTaskAction(Task $task, string $role): void;
}
