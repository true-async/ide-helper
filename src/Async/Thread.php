<?php

declare(strict_types=1);

namespace Async;

/**
 * Represents a running OS thread.
 *
 * Obtain a Thread via {@see spawn_thread()}.
 * Each thread has its own PHP runtime (TSRM) and event loop.
 *
 * Data transfer between threads follows deep-copy semantics:
 * scalars, arrays, objects with declared properties, Closures, WeakReference,
 * WeakMap, and FutureState are transferable. stdClass, PHP references, and
 * resources are not — attempting to transfer them throws ThreadTransferException.
 *
 * @template T
 * @implements Completable<T>
 * @since 8.6
 *
 * @see https://true-async.github.io/en/docs/components/threads.html
 */
final class Thread implements Completable
{
    private function __construct() {}

    /**
     * Return true if the thread is currently running.
     */
    public function isRunning(): bool {}

    /**
     * Return true if the thread has completed execution.
     */
    public function isCompleted(): bool {}

    /**
     * Return true if the thread was cancelled.
     */
    public function isCancelled(): bool {}

    /**
     * Returns the thread result when finished.
     * If the thread is not finished, returns null.
     *
     * @return T
     */
    public function getResult() {}

    /**
     * Returns the thread exception when finished.
     * If the thread is not finished, returns null.
     * @return RemoteException|null
     */
    public function getException(): RemoteException|null {}

    /**
     * Cancel the thread.
     */
    public function cancel(?AsyncCancellation $cancellation = null): void {}

    /**
     * Define a callback to be executed when the thread is finished.
     */
    public function finally(\Closure $callback): void {}
}
