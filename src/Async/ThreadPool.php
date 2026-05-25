<?php

declare(strict_types=1);

namespace Async;

/**
 * Fixed-size pool of reusable OS worker threads for CPU-bound tasks.
 *
 * Worker threads are created once at construction and remain alive until
 * the pool is closed or cancelled. Tasks submitted via {@see submit()} are
 * transferred to workers through an internal {@see ThreadChannel}; each task
 * returns a {@see Future} that resolves with the return value or rejects with
 * the exception thrown inside the worker.
 *
 * The pool object itself may be transferred between OS threads (shared
 * persistent memory with reference counting), so multiple threads can submit
 * tasks to the same pool concurrently.
 *
 * Task callables and their arguments follow the same deep-copy transfer rules
 * as {@see spawn_thread()}: scalars, arrays, objects with declared properties,
 * Closures, WeakReference, WeakMap, and FutureState are accepted; stdClass,
 * PHP references, and resources are rejected with {@see ThreadTransferException}.
 *
 * @since 8.6
 *
 * @see https://true-async.github.io/en/docs/components/thread-pool.html
 */
final class ThreadPool
{
    /**
     * Create a pool with a fixed number of worker threads.
     *
     * Workers start immediately. Destroying the ThreadPool object without
     * calling close() or cancel() first triggers a graceful shutdown.
     *
     * @param int           $workers     Number of worker threads. `0` (default) =
     *                                   auto-detect from available CPU parallelism
     *                                   (see {@see available_parallelism()}).
     * @param int           $queueSize   Maximum number of tasks that may wait in
     *                                   the queue. `0` = default (workers × 4).
     *                                   When the queue is full, submit() suspends
     *                                   the caller until a slot opens.
     * @param \Closure|null $bootloader  Optional closure executed once per worker
     *                                   thread on startup, before any task runs.
     *                                   Typical use: register an autoloader. If
     *                                   the bootloader throws, the pool is failed:
     *                                   the task channel is closed and all pending
     *                                   submissions are rejected with
     *                                   CancellationException.
     * @param bool          $coroutine   When true, each submitted task runs inside
     *                                   its own coroutine in the worker's scheduler
     *                                   instead of being invoked synchronously.
     *                                   Enables `await`, channels, and IO inside
     *                                   tasks without blocking the worker thread,
     *                                   and makes cancel() able to interrupt
     *                                   in-flight tasks.
     * @param int           $concurrency Max concurrent task-coroutines per worker
     *                                   (only meaningful when `coroutine: true`).
     *                                   `0` (default) = unlimited. Total pool
     *                                   concurrency = workers × concurrency.
     */
    public function __construct(int $workers = 0, int $queueSize = 0, ?\Closure $bootloader = null, bool $coroutine = false, int $concurrency = 0) {}

    /**
     * Submit a callable for execution in a worker thread.
     *
     * The callable and any extra arguments are deep-copied to the worker.
     * Returns a Future that resolves with the callable's return value, or
     * rejects if the callable throws.
     *
     * @template T
     * @param callable(mixed...): T $task    The callable to execute in a worker thread.
     * @param mixed    ...$args Extra arguments passed to the callable.
     * @return Future<T>
     * @throws ThreadPoolException     If the pool is closed.
     * @throws ThreadTransferException If the callable or args cannot be transferred.
     */
    public function submit(callable $task, mixed ...$args): Future {}

    /**
     * Apply a callable to each element of an array in parallel.
     *
     * Tasks are distributed across worker threads. Blocks the current
     * coroutine until all tasks complete. Results are returned in the same
     * order as the input array.
     *
     * @template TKey of array-key
     * @template TIn
     * @template TOut
     * @param array<TKey, TIn> $items Input array.
     * @param callable(TIn): TOut $task  Called with each element; return value is collected.
     * @return array<TKey, TOut> Results indexed the same as $items.
     * @throws ThreadPoolException If the pool is closed.
     */
    public function map(array $items, callable $task): array {}

    /**
     * Gracefully shut down the pool.
     *
     * Rejects new submissions immediately. Already-running tasks complete
     * normally; pending (queued) tasks are cancelled with ThreadPoolException.
     */
    public function close(): void {}

    /**
     * Forcefully stop the pool.
     *
     * Rejects all pending (queued) tasks. In coroutine mode (see the
     * `$coroutine` constructor flag), in-flight tasks are hard-cancelled —
     * their coroutines receive an Async\AsyncCancellation and reject the
     * corresponding Future. In synchronous mode, an already-running task
     * cannot be preempted and runs to completion; workers stop afterwards.
     */
    public function cancel(): void {}

    /**
     * Return true if the pool has been closed or cancelled.
     */
    public function isClosed(): bool {}

    /**
     * Return the number of tasks currently waiting in the queue.
     */
    public function getPendingCount(): int {}

    /**
     * Return the number of tasks currently being executed by workers.
     */
    public function getRunningCount(): int {}

    /**
     * Return the total number of tasks completed since the pool was created.
     *
     * This counter is monotonically increasing and includes both successful
     * and failed tasks.
     */
    public function getCompletedCount(): int {}

    /**
     * Return the number of worker threads in the pool.
     */
    public function getWorkerCount(): int {}
}
