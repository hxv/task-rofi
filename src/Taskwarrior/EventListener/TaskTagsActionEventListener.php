<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Menu\MenuInterface;
use hxv\TaskRofi\Taskwarrior\TaskItem;
use hxv\TaskRofi\Menu\Item;
use hxv\TaskRofi\Taskwarrior\Taskwarrior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TaskTagsActionEventListener implements EventSubscriberInterface
{
    public function __construct(private Taskwarrior $taskwarrior, private MenuInterface $menu)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            ItemSelectedEvent::class => [
                ['onTaskMenuCreated'],
                ['onTagsMenuCreated'],
                ['onItemSelected'],
            ],
        ];
    }

    public function onTaskMenuCreated(ItemSelectedEvent $event): void
    {
        if (!$event->stack()->current() instanceof TaskItem) {
            return;
        }

        $event->addMenuItem(new Item('Tags', Role::TASK_TAGS, action: true));
    }

    public function onTagsMenuCreated(ItemSelectedEvent $event): void
    {
        if ($event->stack()->current()->getRole() !== Role::TASK_TAGS) {
            return;
        }

        if (null === $task = $event->stack()->findClass(TaskItem::class)) {
            throw new \LogicException();
        }

        /** @var TaskItem $task */

        $tags = $task->getTask()->tags();
        foreach ($tags as $tag) {
            $event->addMenuItem(new Item($tag, Role::TASK_TAG));
        }

        foreach ($this->taskwarrior->getTags() as $tag) {
            if (!in_array($tag, $tags, true)) {
                $event->addMenuItem(new Item($tag, Role::TASK_TAG, urgent: true));
            }
        }
    }

    public function onItemSelected(ItemSelectedEvent $event): void
    {
        $currentItem = $event->stack()->current();

        if ($currentItem->getRole() !== Role::TASK_TAG) {
            return;
        }

        if (null === $taskItem = $event->stack()->findClass(TaskItem::class)) {
            throw new \LogicException();
        }

        /** @var TaskItem $taskItem */

        if ($currentItem->isUrgent()) {
            $this->taskwarrior->tagTask($taskItem->getTask(), [$currentItem->getLabel()]);
        } else {
            $this->taskwarrior->untagTask($taskItem->getTask(), [$currentItem->getLabel()]);
        }

        $taskItem->setTask($this->taskwarrior->getTask($taskItem->getTask()->uuid()));
    }
}
