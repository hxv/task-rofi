<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Menu\Item;
use hxv\TaskRofi\Menu\MenuInterface;
use hxv\TaskRofi\Taskwarrior\TaskItem;
use hxv\TaskRofi\Taskwarrior\Taskwarrior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TaskTagCreateEventListener implements EventSubscriberInterface
{
    public function __construct(private Taskwarrior $taskwarrior, private MenuInterface $menu)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            ItemSelectedEvent::class => [
                ['onMenuCreated', -10],
                ['onItemSelected']
            ],
        ];
    }

    public function onMenuCreated(ItemSelectedEvent $event): void
    {
        if ($event->stack()->current()->getRole() !== Role::TASK_TAGS) {
            return;
        }

        $event->addMenuItem(new Item('Add tag', Role::TASK_TAGS_ADD, action: true));
    }

    public function onItemSelected(ItemSelectedEvent $event): void
    {
        if ($event->stack()->current()->getRole() !== Role::TASK_TAGS_ADD) {
            return;
        }

        if (null === $task = $event->stack()->findClass(TaskItem::class)) {
            throw new \LogicException();
        }

        /** @var TaskItem $task */

        if (null === $tag = $this->menu->prompt('Tag')) {
            return;
        }

        $this->taskwarrior->tagTask($task->getTask(), [$tag]);

        $task->setTask($this->taskwarrior->getTask($task->getTask()->uuid()));
    }
}
