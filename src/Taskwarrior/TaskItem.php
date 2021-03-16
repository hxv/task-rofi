<?php

namespace hxv\TaskRofi\Taskwarrior;

use hxv\TaskRofi\Menu\ItemInterface;
use hxv\TaskRofi\Taskwarrior\EventListener\Role;

/**
 * Item displaying Taskwarrior's task.
 */
class TaskItem implements ItemInterface
{
    public function __construct(private Task $task)
    {
    }

    public function getLabel(): string
    {
        return $this->task->description();
    }

    public function getRole(): string
    {
        return Role::TASK;
    }

    public function isActive(): bool
    {
        return $this->task->startDate() !== null; // mark tasks with startDate as 'active'
    }

    public function isUrgent(): bool
    {
        return $this->task->status() !== 'pending'; // mark deleted and completed tasks as 'urgent'
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function isAction(): bool
    {
        return false;
    }
}
