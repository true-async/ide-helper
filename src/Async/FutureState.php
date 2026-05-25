<?php

declare(strict_types=1);

namespace Async;

/**
 * Write-side handle for a {@see Future}.
 *
 * FutureState holds the mutable half of the Future pair: it can be resolved
 * (via {@see complete()}) or rejected (via {@see error()}) exactly once.
 * The matching {@see Future} is the read-only consumer side.
 *
 * ## Cross-thread transfer
 *
 * FutureState is the **only** Future-related object that can be transferred
 * between OS threads. Transferring FutureState also transfers **ownership**:
 * only one thread may call complete()/error() — a second transfer throws.
 *
 * Typical pattern:
 * ```php
 * $state  = new FutureState();
 * $future = new Future($state);          // parent keeps Future (read side)
 *
 * spawn_thread(function() use ($state) { // child gets FutureState (write side)
 *     $state->complete(computeResult()); // wakes parent's await($future)
 * });
 *
 * $result = await($future);
 * ```
 *
 * @template T
 * @since 8.6
 */
final class FutureState
{
    public function __construct() {}

    /**
     * Resolve the Future with a result value.
     *
     * @param T $result
     */
    public function complete(mixed $result): void {}

    /**
     * Reject the Future with an error.
     *
     * @param \Throwable $throwable
     */
    public function error(\Throwable $throwable): void {}

    /**
     * Return true if the Future has already been resolved or rejected.
     */
    public function isCompleted(): bool {}

    /**
     * Suppress error forwarding to the event-loop error handler when no
     * consumer handles the error.
     */
    public function ignore(): void {}

    /**
     * Return debug information about awaiters of this FutureState.
     *
     * @return array<string, mixed>
     */
    public function getAwaitingInfo(): array {}

    /**
     * Return the file and line where this FutureState was created.
     *
     * @return array{file: string, line: int}
     */
    public function getCreatedFileAndLine(): array {}

    /**
     * Return the creation location as a human-readable string.
     */
    public function getCreatedLocation(): string {}

    /**
     * Return the file and line where this FutureState was completed.
     *
     * @return array{file: string, line: int}
     */
    public function getCompletedFileAndLine(): array {}

    /**
     * Return the completion location as a human-readable string.
     */
    public function getCompletedLocation(): string {}
}
