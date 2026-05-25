<?php

declare(strict_types=1);

namespace Async;

/**
 * Task pool with queue and concurrency control.
 *
 * Accepts callables, manages coroutine creation with optional concurrency
 * limits, and collects results keyed by task identifier.
 *
 * @implements \IteratorAggregate<int|string, array{0: mixed, 1: ?\Throwable}>
 * @implements Awaitable<array<int|string, mixed>>
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/task-group.html
 */
final class TaskGroup implements Awaitable, \Countable, \IteratorAggregate
{
    /**
     * Create a new TaskGroup.
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
     * @throws AsyncException If the group is closed or cancelled.
     */
    public function spawn(callable $task, mixed ...$args): void {}

    /**
     * Spawn a task with an explicit key.
     *
     * @param string|int $key  Result key (must be unique within the group).
     * @param callable   $task
     * @param mixed      ...$args
     * @throws AsyncException If the group is closed, cancelled, or the key is a duplicate.
     */
    public function spawnWithKey(string|int $key, callable $task, mixed ...$args): void {}

    /**
     * Return a Future that resolves with all task results when every task completes.
     *
     * @param bool $ignoreErrors If false, rejects with {@see CompositeException} on any error.
     * @return Future<array<string|int, mixed>>
     */
    public function all(bool $ignoreErrors = false): Future {}

    /**
     * Return a Future that resolves or rejects with the first settled task.
     *
     * Remaining tasks continue running.
     *
     * @return Future<mixed>
     * @throws AsyncException If the group is empty.
     */
    public function race(): Future {}

    /**
     * Return a Future that resolves with the first successfully completed task.
     *
     * Errors are skipped. If all tasks fail, rejects with {@see CompositeException}.
     * Remaining tasks continue running.
     *
     * @return Future<mixed>
     * @throws AsyncException If the group is empty.
     */
    public function any(): Future {}

    /**
     * Return results of already-completed tasks.
     *
     * @return array<string|int, mixed>
     */
    public function getResults(): array {}

    /**
     * Return errors of failed tasks and mark them as handled.
     *
     * @return array<string|int, \Throwable>
     */
    public function getErrors(): array {}

    /**
     * Mark all pending errors as handled without retrieving them.
     */
    public function suppressErrors(): void {}

    /**
     * Cancel all running coroutines and discard queued tasks.
     *
     * Implicitly calls {@see close()}.
     *
     * @param AsyncCancellation|null $cancellation
     */
    public function cancel(?AsyncCancellation $cancellation = null): void {}

    /**
     * Close the group: no new tasks may be added.
     *
     * Running and queued tasks continue normally.
     */
    public function close(): void {}

    /**
     * Dispose of the group's scope, cancelling all coroutines.
     */
    public function dispose(): void {}

    /**
     * Return true if the queue is empty and no coroutines are active.
     *
     * This state may be temporary if the group is not yet closed.
     */
    public function isFinished(): bool {}

    /**
     * Return true if the group has been closed.
     */
    public function isClosed(): bool {}

    /**
     * Return the total number of tasks (queued + running + completed).
     */
    public function count(): int {}

    /**
     * Suspend the current coroutine until all tasks finish.
     *
     * The group **must** be closed before calling this method.
     * Unlike {@see all()}, this method never throws on task errors.
     *
     * @throws AsyncException If the group is not closed.
     */
    public function awaitCompletion(): void {}

    /**
     * Register a callback invoked when the group is closed and all tasks complete.
     *
     * If the group is already in that state, the callback is invoked immediately.
     *
     * @param \Closure $callback Receives the TaskGroup as its argument.
     */
    public function finally(\Closure $callback): void {}

    /**
     * Return an iterator that yields results as tasks complete.
     *
     * Each iteration yields `[$result, null]` on success or `[null, $error]`
     * on failure, keyed by the task key. Errors are marked as handled on
     * delivery. Iteration ends when the group is closed and all tasks are
     * delivered.
     *
     * @return \Iterator<string|int, array{0: mixed, 1: \Throwable|null}>
     */
    public function getIterator(): \Iterator {}
}
