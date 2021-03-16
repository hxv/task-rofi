<?php

namespace hxv\TaskRofi\Menu;

/**
 * Top-level menu item.
 *
 * Item is added after creating menu.
 */
class TopItem implements ItemInterface
{
    const ROLE = 'menu';

    public function __construct(private string $label)
    {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getRole(): string
    {
        return self::ROLE;
    }

    public function isActive(): bool
    {
        return false;
    }

    public function isUrgent(): bool
    {
        return false;
    }

    public function isAction(): bool
    {
        return false;
    }
}
