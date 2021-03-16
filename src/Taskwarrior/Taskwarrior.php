<?php

namespace hxv\TaskRofi\Taskwarrior;

use Symfony\Component\Process\Process;
use Symfony\Component\Serializer\SerializerInterface;

class Taskwarrior
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return Task[]
     */
    public function getTasks(): array
    {
        $process = new Process([
            'task',
            'rc.verbose=nothing',
            'rc.report.rofi.columns=uuid',
            'rc.report.rofi.labels=',
//            'rc.report.rofi.filter=status:pending or status:completed',
            'rc.report.rofi.filter=status:pending', // TODO - allow status selection
            'rc.report.rofi.sort=start-',
            'rofi',
        ]);

        $process->run();

        $tasks = [];
        foreach (explode("\n", $process->getOutput()) as $uuid) {
            if ('' === $uuid = trim($uuid)) {
                continue;
            }

            $tasks[] = $this->getTask($uuid);
        }

        return $tasks;
    }

    public function getTask(string $uuidOrId): Task
    {
        $p = (new Process(['task', $uuidOrId, 'export', 'rc.json.array=off']))->mustRun();

        /** @var Task $task */
        $task = $this->serializer->deserialize($p->getOutput(), Task::class, 'json');

        return $task;
    }

    public function addTask(string $description): Task
    {
        $output = (new Process(['task', 'add', $description]))->mustRun()->getOutput();

        if (preg_match('/^Created task (?<id>\\d+)\\./', $output, $matches) !== 1) {
            throw new \RuntimeException('Could not create task.');
        }

        return $this->getTask($matches['id']);
    }

    public function removeTask(Task $task): void
    {
        (new Process(['task', $task->uuid(), 'delete', 'rc.confirmation=off']))->mustRun();
    }

    public function closeTask(Task $task): void
    {
        (new Process(['task', $task->uuid(), 'done']))->mustRun();
    }

    public function reopenTask(Task $task): void
    {
        (new Process(['task', $task->uuid(), 'modify', 'status:pending']))->mustRun();
    }

    public function modifyTask(Task $task, string $description): void
    {
        (new Process(['task', $task->uuid(), 'modify', "description:{$description}"]))->mustRun();
    }

    public function startTask(Task $task): void
    {
        (new Process(['task', $task->uuid(), 'start']))->mustRun();
    }

    public function stopTask(Task $task): void
    {
        (new Process(['task', $task->uuid(), 'stop']))->mustRun();
    }

    /**
     * @param string[] $tags
     */
    public function tagTask(Task $task, array $tags): void
    {
        $tags = array_map(fn(string $tag): string => preg_replace('/[^\\w]/', '', $tag), $tags);
        $tags = array_filter($tags, fn(string $tag): bool => $tag !== '');
        $tags = array_map(fn(string $tag): string => "+{$tag}", $tags);

        (new Process(['task', $task->uuid(), 'modify', ...$tags]))->mustRun();
    }

    /**
     * @param string[] $tags
     */
    public function untagTask(Task $task, array $tags): void
    {
        $tags = array_map(fn(string $tag): string => preg_replace('/[^\\w]/', '', $tag), $tags);
        $tags = array_filter($tags, fn(string $tag): bool => $tag !== '');
        $tags = array_map(fn(string $tag): string => "-{$tag}", $tags);

        (new Process(['task', $task->uuid(), 'modify', ...$tags]))->mustRun();
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        $process = new Process([
            'task',
            'rc.verbose=nothing',
            'rc.list.all.tags=1',
            'tags',
        ]);

        $process->mustRun();

        $tags = [];
        foreach (explode("\n", $process->getOutput()) as $tag) {
            $tag = preg_replace('/\\s+\\d+$/', '', $tag);

            if ('' === $tag = trim($tag)) {
                continue;
            }

            $tags[] = $tag;
        }

        return $tags;
    }

    // TODO - priorytety
    // TODO - timewarrior
}
