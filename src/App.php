<?php

namespace hxv\TaskRofi;

use hxv\TaskRofi\Event\ItemSelectedEvent;
use hxv\TaskRofi\Event\ItemStack;
use hxv\TaskRofi\Menu\ItemInterface;
use hxv\TaskRofi\Menu\MenuInterface;
use hxv\TaskRofi\Menu\TopItem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class App
{
    public function __construct(private EventDispatcherInterface $eventDispatcher, private MenuInterface $menu)
    {
    }

    public function run(string $menu): void
    {
        $stack = new ItemStack([new TopItem($menu)]);

        /** @var ItemSelectedEvent $itemSelectedEvent */
        $itemSelectedEvent = $this->eventDispatcher->dispatch(new ItemSelectedEvent($stack));

        do {
            if ([] === $items = $itemSelectedEvent->getMenuItems()) {
                break;
            }

            if (null === $selectedItem = $this->menu->show($items)) {
                break;
            }

            $stack->push($selectedItem);

            /** @var ItemSelectedEvent $itemSelectedEvent */
            $itemSelectedEvent = $this->eventDispatcher->dispatch(new ItemSelectedEvent($stack));

            if ($itemSelectedEvent->getRestart()) {
                /** @var ItemSelectedEvent $itemSelectedEvent */
                $itemSelectedEvent = $this->eventDispatcher->dispatch(new ItemSelectedEvent($stack));
            }

            if ($itemSelectedEvent->getMenuItems() === [] && !$itemSelectedEvent->getCloseMenu()) {
                // TODO - I'm not too happy with this :/

                $stack->pop();

                /** @var ItemSelectedEvent $itemSelectedEvent */
                $itemSelectedEvent = $this->eventDispatcher->dispatch(new ItemSelectedEvent($stack));
            }
        } while (true);
    }
}
