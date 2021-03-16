<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Menu\MenuInterface;
use hxv\TaskRofi\Menu\Item;
use hxv\TaskRofi\Taskwarrior\Task;
use hxv\TaskRofi\Taskwarrior\Taskwarrior;

class TaskRenameActionEventListener extends AbstractTaskActionEventListener
{
    public function __construct(Taskwarrior $taskwarrior, private MenuInterface $menu)
    {
        parent::__construct($taskwarrior);
    }

    protected static function getPriority(): int
    {
        return -5;
    }

    protected function taskMatches(Task $task): bool
    {
        return true;
    }

    protected function getTaskActions(Task $task): array
    {
        return [new Item('Rename', Role::TASK_RENAME, action: true)];
    }

    protected function handleTaskAction(Task $task, string $role): void
    {
        if ($role !== Role::TASK_RENAME) {
            return;
        }

        if (null === $description = $this->menu->prompt('Description', $task->description())) {
            return;
        }

        $this->taskwarrior->modifyTask($task, $description);
    }
}
