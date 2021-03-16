<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Menu\Item;
use hxv\TaskRofi\Menu\MenuInterface;
use hxv\TaskRofi\Menu\TopItem;
use hxv\TaskRofi\Taskwarrior\TaskItem;
use hxv\TaskRofi\Taskwarrior\Taskwarrior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddTaskEventListener implements EventSubscriberInterface
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
        if ($event->stack()->current()->getRole() !== TopItem::ROLE || $event->stack()->current()->getLabel() !== Role::MENU) {
            return;
        }

        $event->addMenuItem(new Item('Create task', Role::ADD_TASK, action: true));
    }

    public function onItemSelected(ItemSelectedEvent $event): void
    {
        if ($event->stack()->current()->getRole() !== Role::ADD_TASK) {
            return;
        }

        if (null === $description = $this->menu->prompt('Description')) {
            return;
        }

        $task = $this->taskwarrior->addTask($description);

        $event->stack()->pop();
        $event->stack()->push(new TaskItem($task));

        $event->setRestart(true);
    }
}
