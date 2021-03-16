<?php

namespace hxv\TaskRofi\Menu;

interface MenuInterface
{
    /**
     * Displays menu with given items.
     *
     * @param non-empty-list<ItemInterface> $items
     */
    public function show(array $items): ?ItemInterface;

    /**
     * Prompts user for text input.
     */
    public function prompt(string $prompt, string $default = ''): ?string;
}
