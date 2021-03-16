<?php

namespace hxv\TaskRofi\Menu;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Implementation of Rofi menu.
 */
class RofiMenu implements MenuInterface
{
    /**
     * @param non-empty-list<ItemInterface> $items
     */
    public function show(array $items): ?ItemInterface
    {
        $urgent = array_keys(array_filter($items, fn (ItemInterface $item): bool => $item->isUrgent()));
        $active = array_keys(array_filter($items, fn (ItemInterface $item): bool => $item->isActive()));

        $input = array_map(fn(ItemInterface $item): string => $item->isAction() ? "<i>&lt;{$item->getLabel()}&gt;</i>" : $item->getLabel(), $items); // add markup to action items

        $input = implode("\n", $input);

        $index = $this->run([
            'rofi',
            '-dmenu',
            '-markup-rows',
            '-format', 'i', // return index of selected item
            '-u', implode(',', $urgent), // mark urgent items
            '-a', implode(',', $active), // mark active items
        ], $input);

        if ($index === null) {
            return null;
        }

        return $items[(int) $index];
    }

    public function prompt(string $prompt, string $default = ''): ?string
    {
        return $this->run([
            'rofi',
            '-dmenu',
            '-l', '0', // disable list
            '-separator-style', 'none', // disable separator (looks nicer)
            '-p', $prompt,
            '-filter', $default, // value of "-filter" is filled as input
        ], '');
    }

    /**
     * @param string[] $command
     */
    private function run(array $command, string $input): ?string
    {
        $process = new Process($command, input: $input);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            /** @psalm-suppress MixedMethodCall */
            if ($exception->getProcess()->getExitCode() === 1) { // exit code 1 = user closed rofi/did not select an item
                return null;
            }

            throw $exception;
        }

        if ('' === $output = trim($process->getOutput())) {
            return null;
        }

        return $output;
    }
}
