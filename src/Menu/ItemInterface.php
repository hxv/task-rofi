<?php

namespace hxv\TaskRofi\Menu;

interface ItemInterface
{
    /**
     * Returns label to be displayed in menu.
     */
    public function getLabel(): string;

    /**
     * Returns item's role.
     */
    public function getRole(): string;

    /**
     * Returns if element should be marked as active.
     *
     * Menu _can_ display such item with different style.
     */
    public function isActive(): bool;

    /**
     * Returns if element should be marked as urgent.
     *
     * Menu _can_ display such item with different style.
     */
    public function isUrgent(): bool;

    /**
     * Returns if element is "action item".
     *
     * Menu _can_ display such item with different style.
     */
    public function isAction(): bool;
}
