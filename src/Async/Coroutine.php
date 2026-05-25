<?php

declare(strict_types=1);

namespace Async;

/**
 * Represents a running or suspended asynchronous coroutine.
 *
 * Coroutines are created via {@see spawn()} or {@see Scope::spawn()}.
 * They implement {@see Completable} and can be awaited by other coroutines.
 *
 * @template T
 * @implements Completable<T>
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/coroutine.html
 */
final class Coroutine implements Completable
{
    /**
     * Return the numeric coroutine ID.
     */
    public function getId(): int {}

    /**
     * Mark the coroutine as high-priority and return it.
     *
     * @return Coroutine $this
     */
    public function asHiPriority(): Coroutine {}

    /**
     * Return the local context of this coroutine.
     */
    public function getContext(): Context {}

    /**
     * Return the coroutine result, or null if it has not finished yet.
     *
     * @return T
     */
    public function getResult() {}

    /**
     * Return the exception that terminated the coroutine, or null if it has
     * not finished or finished successfully.
     *
     * If the coroutine was cancelled, returns an {@see AsyncCancellation}.
     * @return \Throwable|null
     * @throws \RuntimeException If the coroutine is still running.
     */
    public function getException(): \Throwable|null {}

    /**
     * Return the backtrace of the suspended coroutine, or null if it is not suspended.
     *
     * @param int $options {@see DEBUG_BACKTRACE_PROVIDE_OBJECT}, {@see DEBUG_BACKTRACE_IGNORE_ARGS}
     * @param int $limit   Maximum number of stack frames (0 = unlimited).
     * @return array<int, array<string, mixed>>|null
     */
    public function getTrace(int $options = DEBUG_BACKTRACE_PROVIDE_OBJECT, int $limit = 0): ?array {}

    /**
     * Return an array with the file and line where the coroutine was spawned.
     *
     * @return array{file: string, line: int}
     */
    public function getSpawnFileAndLine(): array {}

    /**
     * Return the spawn location as a human-readable string.
     */
    public function getSpawnLocation(): string {}

    /**
     * Return an array with the file and line where the coroutine is currently suspended.
     *
     * @return array{file: string, line: int}
     */
    public function getSuspendFileAndLine(): array {}

    /**
     * Return the suspend location as a human-readable string.
     */
    public function getSuspendLocation(): string {}

    /**
     * Return true if the coroutine has been started.
     */
    public function isStarted(): bool {}

    /**
     * Return true if the coroutine is waiting in the run-queue.
     */
    public function isQueued(): bool {}

    /**
     * Return true if the coroutine is actively executing.
     */
    public function isRunning(): bool {}

    /**
     * Return true if the coroutine is currently suspended.
     */
    public function isSuspended(): bool {}

    /**
     * Return true if the coroutine has been cancelled.
     */
    public function isCancelled(): bool {}

    /**
     * Return true if a cancellation has been requested but not yet delivered.
     */
    public function isCancellationRequested(): bool {}

    /**
     * Return true if the coroutine has finished (success, error, or cancellation).
     */
    public function isCompleted(): bool {}

    /**
     * Return debug information about what this coroutine is currently awaiting.
     *
     * @return array<string, mixed>
     */
    public function getAwaitingInfo(): array {}

    /**
     * Request cancellation of the coroutine.
     *
     * @param AsyncCancellation|null $cancellation Optional cancellation reason.
     */
    public function cancel(?AsyncCancellation $cancellation = null): void {}

    /**
     * Register a callback to be executed when the coroutine finishes.
     *
     * @param \Closure $callback Invoked with no arguments after the coroutine completes.
     */
    public function finally(\Closure $callback): void {}
}
