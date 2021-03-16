<?php

namespace hxv\TaskRofi\Event;

use ArrayIterator;
use hxv\TaskRofi\Menu\ItemInterface;
use IteratorAggregate;

/**
 * Stack of selected items.
 *
 * @implements IteratorAggregate<ItemInterface>
 */
class ItemStack implements IteratorAggregate
{
    /**
     * @param non-empty-list<ItemInterface> $stack
     */
    public function __construct(private array $stack)
    {
    }

    /**
     * Adds selected item to the stack.
     */
    public function push(ItemInterface $item): void
    {
        array_unshift($this->stack, $item);
    }

    /**
     * Pops item off the stack.
     */
    public function pop(): ?ItemInterface
    {
        if (count($this->stack) === 1) {
            return null;
        }

        return array_shift($this->stack) ?: null;
    }

    /**
     * Returns current (last selected) item.
     */
    public function current(): ItemInterface
    {
        return $this->stack[0];
    }

    /**
     * Returns an item with given offset.
     *
     * `null` is returned if no item with given offset is on the stack.
     */
    public function get(int $offset): ?ItemInterface
    {
        return $this->stack[$offset] ?? null;
    }

    /**
     * @return ArrayIterator<int, ItemInterface>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->stack);
    }

    /**
     * Returns first item with given class.
     *
     * @template T of ItemInterface
     *
     * @param class-string<T> $class
     *
     * @return T|null
     */
    public function findClass(string $class): ?ItemInterface
    {
        /** @var T|null $item */
        $item = $this->find(fn(ItemInterface $item): bool => get_class($item) === $class);

        return $item;
    }

    /**
     * Returns first item with given role.
     */
    public function findRole(string $role): ?ItemInterface
    {
        return $this->find(fn(ItemInterface $item): bool => $item->getRole() === $role);
    }

    /**
     * @param callable(ItemInterface):bool $callback
     */
    private function find(callable $callback): ?ItemInterface
    {
        foreach ($this->stack as $item) {
            if ($callback($item)) {
                return $item;
            }
        }

        return null;
    }
}
