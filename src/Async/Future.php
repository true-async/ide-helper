<?php

declare(strict_types=1);

namespace Async;

/**
 * Read-side handle for an asynchronous operation.
 *
 * A Future is completed by its paired {@see FutureState}. It can be
 * chained via {@see map()}, {@see catch()}, and {@see finally()}, and
 * awaited inside a coroutine via {@see await()}.
 *
 * @template T
 * @implements Completable<T>
 * @since 8.6
 *
 * @see https://true-async.github.io/en/docs/components/future.html
 */
final class Future implements Completable
{
    /**
     * Create an already-resolved Future.
     *
     * @template Tv
     * @param Tv $value
     * @return Future<Tv>
     */
    public static function completed(mixed $value = null): Future {}

    /**
     * Create an already-rejected Future.
     *
     * @return Future<never>
     */
    public static function failed(\Throwable $throwable): Future {}

    /**
     * Create a Future backed by the given FutureState.
     *
     * @param FutureState<T> $state
     */
    public function __construct(FutureState $state) {}

    /**
     * Return true if the Future has been resolved or rejected.
     */
    public function isCompleted(): bool {}

    /**
     * Return true if the Future was cancelled.
     */
    public function isCancelled(): bool {}

    /**
     * Request cancellation of the Future.
     *
     * @param AsyncCancellation|null $cancellation
     */
    public function cancel(?AsyncCancellation $cancellation = null): void {}

    /**
     * Suppress error forwarding to the event-loop handler.
     *
     * @return Future<T>
     */
    public function ignore(): Future {}

    /**
     * Transform the resolved value.
     *
     * The returned Future resolves with the return value of $map, or rejects
     * if $map throws.
     *
     * @template Tr
     * @param callable(T): Tr $map
     * @return Future<Tr>
     */
    public function map(callable $map): Future {}

    /**
     * Handle a rejection.
     *
     * The returned Future resolves with the return value of $catch, or
     * re-rejects if $catch throws.
     *
     * @template Tr
     * @param callable(\Throwable): Tr $catch
     * @return Future<T|Tr>
     */
    public function catch(callable $catch): Future {}

    /**
     * Attach a callback that runs regardless of success or failure.
     *
     * The returned Future carries the same result as this Future after the
     * callback completes. If the callback throws, the returned Future rejects
     * with that exception.
     *
     * @param callable(): void $finally
     * @return Future<T>
     */
    public function finally(callable $finally): Future {}

    /**
     * Suspend the current coroutine until this Future is settled.
     *
     * @param Completable|null $cancellation Optional cancellation token.
     * @return T
     * @throws \Throwable If the Future was rejected.
     * @throws OperationCanceledException If the cancellation token fires.
     */
    public function await(?Completable $cancellation = null) {}

    /**
     * Return debug information about awaiters of this Future.
     *
     * @return array<string, mixed>
     */
    public function getAwaitingInfo(): array {}

    /**
     * Return the file and line where this Future was created.
     *
     * @return array{file: string, line: int}
     */
    public function getCreatedFileAndLine(): array {}

    /**
     * Return the creation location as a human-readable string.
     */
    public function getCreatedLocation(): string {}

    /**
     * Return the file and line where this Future was completed.
     *
     * @return array{file: string, line: int}
     */
    public function getCompletedFileAndLine(): array {}

    /**
     * Return the completion location as a human-readable string.
     */
    public function getCompletedLocation(): string {}
}
