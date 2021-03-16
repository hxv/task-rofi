<?php

namespace hxv\TaskRofi\Menu;

/**
 * Simple menu item.
 */
class Item implements ItemInterface
{
    public function __construct(private string $label, private string $role, private bool $active = false, private bool $urgent = false, private bool $action = false)
    {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isUrgent(): bool
    {
        return $this->urgent;
    }

    public function isAction(): bool
    {
        return $this->action;
    }
}
