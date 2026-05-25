<?php

declare(strict_types=1);

namespace Async;

/**
 * Represents a single filesystem event detected by {@see FileSystemWatcher}.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/filesystem-watcher.html
 */
final readonly class FileSystemEvent
{
    /** Absolute path of the watched file or directory. */
    public string $path;

    /** Name of the file that changed, or null when unavailable. */
    public ?string $filename;

    /** True if the entry was renamed or moved. */
    public bool $renamed;

    /** True if the entry content was modified. */
    public bool $changed;
}
