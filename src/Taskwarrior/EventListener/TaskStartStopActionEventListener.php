<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Menu\Item;
use hxv\TaskRofi\Taskwarrior\Task;

class TaskStartStopActionEventListener extends AbstractTaskActionEventListener
{
    protected static function getPriority(): int
    {
        return 10;
    }

    protected function taskMatches(Task $task): bool
    {
        return $task->status() === 'pending';
    }

    protected function getTaskActions(Task $task): array
    {
        return [
            $task->startDate() === null
                ? new Item('Start', Role::TASK_START, action: true)
                : new Item('Stop', Role::TASK_STOP, action: true),
        ];
    }

    protected function handleTaskAction(Task $task, string $role): void
    {
        switch ($role) {
            case Role::TASK_START:
                $this->taskwarrior->startTask($task);
                break;

            case Role::TASK_STOP:
                $this->taskwarrior->stopTask($task);
                break;
        }
    }
}
