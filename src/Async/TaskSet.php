<?php

declare(strict_types=1);

namespace Async;

/**
 * Mutable task collection with automatic cleanup.
 *
 * Completed tasks are automatically removed from the set after their results
 * are consumed via {@see joinNext()}, {@see joinAny()}, {@see joinAll()},
 * or foreach iteration. This makes TaskSet ideal for worker-pool patterns
 * where tasks are spawned dynamically and results are processed as they arrive.
 *
 * @implements \IteratorAggregate<int|string, array{0: mixed, 1: ?\Throwable}>
 * @implements Awaitable<array<int|string, mixed>>
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/task-set.html
 */
final class TaskSet implements Awaitable, \Countable, \IteratorAggregate
{
    /**
     * Create a new TaskSet.
     *
     * @param int|null   $concurrency Maximum concurrent coroutines; null = unlimited.
     * @param Scope|null $scope       Parent scope; null = current scope.
     */
    public function __construct(?int $concurrency = null, ?Scope $scope = null) {}

    /**
     * Spawn a task with an auto-increment key.
     *
     * If the concurrency limit is not reached, a coroutine starts immediately;
     * otherwise the callable is queued.
     *
     * @param callable $task
     * @param mixed    ...$args
     * @throws AsyncException If the set is closed or cancelled.
     */
    public function spawn(callable $task, mixed ...$args): void {}

    /**
     * Spawn a task with an explicit key.
     *
     * @param string|int $key  Result key (must be unique within the set).
     * @param callable   $task
     * @param mixed      ...$args
     * @throws AsyncException If the set is closed, cancelled, or the key is a duplicate.
     */
    public function spawnWithKey(string|int $key, callable $task, mixed ...$args): void {}

    /**
     * Return a Future that resolves or rejects with the first settled task.
     *
     * The completed entry is automatically removed from the set.
     * Remaining tasks continue running.
     *
     * @return Future<mixed>
     * @throws AsyncException If the set is empty.
     */
    public function joinNext(): Future {}

    /**
     * Return a Future that resolves with the first successfully completed task.
     *
     * Errors are skipped. If all tasks fail, rejects with {@see CompositeException}.
     * The completed entry is automatically removed from the set.
     * Remaining tasks continue running.
     *
     * @return Future<mixed>
     * @throws AsyncException If the set is empty.
     */
    public function joinAny(): Future {}

    /**
     * Return a Future that resolves with all task results.
     *
     * All entries are automatically removed from the set after delivery.
     *
     * @param bool $ignoreErrors If false, rejects with {@see CompositeException} on any error.
     * @return Future<array<string|int, mixed>>
     */
    public function joinAll(bool $ignoreErrors = false): Future {}

    /**
     * Cancel all running coroutines and discard queued tasks.
     *
     * Implicitly calls {@see close()}.
     *
     * @param AsyncCancellation|null $cancellation
     */
    public function cancel(?AsyncCancellation $cancellation = null): void {}

    /**
     * Close the set: no new tasks may be added.
     *
     * Running and queued tasks continue normally.
     */
    public function close(): void {}

    /**
     * Dispose of the set's scope, cancelling all coroutines.
     */
    public function dispose(): void {}

    /**
     * Return true if no coroutines are active and no tasks are queued.
     *
     * This state may be temporary if the set is not yet closed.
     */
    public function isFinished(): bool {}

    /**
     * Return true if the set has been closed.
     */
    public function isClosed(): bool {}

    /**
     * Return the number of tasks currently in the set.
     *
     * Decreases as completed tasks are consumed via join methods or iteration.
     */
    public function count(): int {}

    /**
     * Suspend the current coroutine until all tasks finish.
     *
     * The set **must** be closed before calling this method.
     * Unlike {@see joinAll()}, this method never throws on task errors.
     *
     * @throws AsyncException If the set is not closed.
     */
    public function awaitCompletion(): void {}

    /**
     * Register a callback invoked when the set is closed and all tasks complete.
     *
     * If the set is already in that state, the callback is invoked immediately.
     *
     * @param \Closure $callback Receives the TaskSet as its argument.
     */
    public function finally(\Closure $callback): void {}

    /**
     * Return an iterator that yields results as tasks complete.
     *
     * Each iteration yields `[$result, null]` on success or `[null, $error]`
     * on failure, keyed by the task key. Consumed entries are automatically
     * removed from the set. Iteration ends when the set is closed and all
     * tasks are delivered.
     *
     * @return \Iterator<string|int, array{0: mixed, 1: \Throwable|null}>
     */
    public function getIterator(): \Iterator {}
}
