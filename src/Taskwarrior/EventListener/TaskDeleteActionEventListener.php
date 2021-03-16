<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Menu\Item;
use hxv\TaskRofi\Taskwarrior\Task;

class TaskDeleteActionEventListener extends AbstractTaskActionEventListener
{
    protected static function getPriority(): int
    {
        return -10;
    }

    protected function taskMatches(Task $task): bool
    {
        return $task->status() !== 'deleted' && $task->startDate() === null;
    }

    protected function getTaskActions(Task $task): array
    {
        return [new Item('Delete', Role::TASK_DELETE, action: true)];
    }

    protected function handleTaskAction(Task $task, string $role): void
    {
        if ($role !== Role::TASK_DELETE) {
            return;
        }

        $this->taskwarrior->removeTask($task);
    }
}
