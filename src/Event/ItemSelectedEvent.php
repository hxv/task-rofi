<?php

namespace hxv\TaskRofi\Event;

use hxv\TaskRofi\Menu\ItemInterface;

class ItemSelectedEvent
{
    private bool $closeMenu = false;

    private bool $restart = false; // TODO - :/

    /** @var list<ItemInterface> */
    private array $menuItems = [];

    public function __construct(private ItemStack $stack)
    {
    }

    /**
     * Returns stack of selected items.
     */
    public function stack(): ItemStack
    {
        return $this->stack;
    }

    /**
     * Adds item to be displayed in next menu.
     *
     * If no items are added previous menu will be displayed again.
     */
    public function addMenuItem(ItemInterface $menuItem): void
    {
        $this->menuItems[] = $menuItem;
    }

    /**
     * Returns items that should be displayed in next menu.
     *
     * @return list<ItemInterface>
     */
    public function getMenuItems(): array
    {
        return $this->menuItems;
    }

    /**
     * Sets if menu should be closed after taking action (instead of displaying it again).
     */
    public function setCloseMenu(bool $closeMenu): void
    {
        $this->closeMenu = $closeMenu;
    }

    /**
     * Returns if menu should be closed after taking action.
     */
    public function getCloseMenu(): bool
    {
        return $this->closeMenu;
    }

    public function setRestart(bool $restart): void
    {
        $this->restart = $restart;
    }

    public function getRestart(): bool
    {
        return $this->restart;
    }
}
