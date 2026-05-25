<?php

declare(strict_types=1);

namespace Async;

/**
 * Persistent filesystem watcher that buffers events for async iteration.
 *
 * Monitors a file or directory for changes and delivers {@see FileSystemEvent}
 * objects via `foreach` or by awaiting the watcher directly.
 *
 * Two buffering modes:
 *  - **coalesce** (`true`): merge multiple events per file into one.
 *  - **raw** (`false`): deliver every event individually.
 *
 * @implements \IteratorAggregate<int, FileSystemEvent>
 * @implements Awaitable<FileSystemEvent>
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/filesystem-watcher.html
 */
final class FileSystemWatcher implements Awaitable, \IteratorAggregate
{
    /**
     * Create a watcher and start monitoring immediately.
     *
     * @param string $path      Absolute or relative path to watch.
     * @param bool   $recursive Watch subdirectories recursively.
     * @param bool   $coalesce  Merge events per file (true) or deliver every event (false).
     */
    public function __construct(string $path, bool $recursive = false, bool $coalesce = true) {}

    /**
     * Stop monitoring and terminate any active iteration.
     *
     * Idempotent — safe to call multiple times.
     */
    public function close(): void {}

    /**
     * Return true if the watcher has been closed.
     */
    public function isClosed(): bool {}

    /**
     * Return an async iterator for `foreach` support.
     *
     * Yields {@see FileSystemEvent} objects as filesystem changes are detected.
     * Suspends when no events are pending; resumes on the next event.
     * Ends when {@see close()} is called or the owning scope is cancelled.
     *
     * @return \Iterator<int, FileSystemEvent>
     */
    public function getIterator(): \Iterator {}
}
