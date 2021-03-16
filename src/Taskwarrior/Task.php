<?php

namespace hxv\TaskRofi\Taskwarrior;

use DateTimeInterface;

/**
 * Taskwarrior's task.
 *
 * @TODO - add descriptions to methods
 * @TODO - verify and fix data types
 */
class Task
{
    /**
     * @param null|string[] $tags
     * @param null|string[] $UDA
     */
    public function __construct(
        private string $status,
        private string $uuid,
        private DateTimeInterface $entry,
        private string $description,
        private ?DateTimeInterface $start = null,
        private ?DateTimeInterface $end = null,
        private ?DateTimeInterface $due = null,
        private ?DateTimeInterface $until = null,
        private ?DateTimeInterface $scheduled = null,
        private ?DateTimeInterface $wait = null,
        private ?string $recur = null,
        private ?string $mask = null,
        private ?int $imask = null,
        private ?string $parent = null,
        private ?string $annotation = null,
        private ?string $project = null,
        private ?array $tags = null,
        private ?string $priority = null,
        private ?string $depends = null,
        private ?DateTimeInterface $modified = null,
        private ?array $UDA = null,
    ) {
    }

    public function status(): string
    {
        return $this->status;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function entryDate(): DateTimeInterface
    {
        return $this->entry;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function startDate(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function endDate(): ?DateTimeInterface
    {
        return $this->end;
    }

    public function dueDate(): ?DateTimeInterface
    {
        return $this->due;
    }

    public function untilDate(): ?DateTimeInterface
    {
        return $this->until;
    }

    public function scheduledDate(): ?DateTimeInterface
    {
        return $this->scheduled;
    }

    public function waitDate(): ?DateTimeInterface
    {
        return $this->wait;
    }

    public function recur(): ?string
    {
        return $this->recur;
    }

    public function mask(): ?string
    {
        return $this->mask;
    }

    public function imask(): ?int
    {
        return $this->imask;
    }

    public function parent(): ?string
    {
        return $this->parent;
    }

    public function annotation(): ?string
    {
        return $this->annotation;
    }

    public function project(): ?string
    {
        return $this->project;
    }

    /**
     * @return string[]
     */
    public function tags(): array
    {
        return $this->tags ?? [];
    }

    public function priority(): ?string
    {
        return $this->priority;
    }

    public function depends(): ?string
    {
        return $this->depends;
    }

    public function modifiedDate(): ?DateTimeInterface
    {
        return $this->modified;
    }

    /**
     * @return string[]
     */
    public function UDA(): array
    {
        return $this->UDA ?? [];
    }
}
