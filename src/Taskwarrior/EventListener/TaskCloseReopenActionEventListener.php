<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Menu\Item;
use hxv\TaskRofi\Taskwarrior\Task;

/**
 * @TODO - chcę to rozdzielać na dwie klasy?
 */
class TaskCloseReopenActionEventListener extends AbstractTaskActionEventListener
{
    protected function taskMatches(Task $task): bool
    {
        switch (true)
        {
            case $task->status() === 'pending' && $task->startDate() === null:
            case $task->status() === 'completed':
                return true;
        }

        return false;
    }

    protected function getTaskActions(Task $task): array
    {
        if ($task->status() === 'pending' && $task->startDate() === null) {
            return [new Item('Close', Role::TASK_CLOSE, action: true)];
        }

        if ($task->status() === 'completed') {
            return [new Item('Reopen', Role::TASK_OPEN, action: true)];
        }

        return [];
    }

    protected function handleTaskAction(Task $task, string $role): void
    {
        switch ($role) {
            case Role::TASK_CLOSE:
                $this->taskwarrior->closeTask($task);
                break;

            case Role::TASK_OPEN:
                $this->taskwarrior->reopenTask($task);
                break;
        }
    }
}
