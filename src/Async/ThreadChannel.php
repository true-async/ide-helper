<?php

declare(strict_types=1);

namespace Async;

/**
 * Thread-safe buffered channel for message passing between OS threads.
 *
 * Unlike {@see Channel} (coroutine-only, single-thread), ThreadChannel uses
 * persistent shared memory and a pthread mutex so that send() and recv() can
 * be called concurrently from different OS threads.
 *
 * Each value is deep-copied into shared memory on send() and back into the
 * receiving thread's heap on recv(). The same transfer rules apply as for
 * {@see spawn_thread()}: stdClass, PHP references, and resources are rejected
 * with {@see ThreadTransferException}.
 *
 * Always buffered (capacity ≥ 1). Rendezvous semantics (capacity = 0) are
 * not supported.
 *
 * A ThreadChannel handle may itself be transferred via spawn_thread() so
 * that multiple threads share the same underlying channel.
 *
 * @template T
 * @implements Awaitable<T>
 * @since 8.6
 *
 * @see https://true-async.github.io/en/docs/components/thread-channels.html
 */
final class ThreadChannel implements Awaitable, \Countable
{
    /**
     * Create a new thread-safe channel.
     *
     * @param int $capacity Buffer size (must be ≥ 1, default 16).
     */
    public function __construct(int $capacity = 16) {}

    /**
     * Send a value into the channel.
     *
     * Suspends the current coroutine until buffer space is available (if the
     * buffer is full). The value is deep-copied into persistent shared memory.
     *
     * @param T            $value
     * @param Completable|null $cancellationToken Optional cancellation token.
     * @throws ThreadChannelException      If the channel is closed.
     * @throws ThreadTransferException     If the value cannot be transferred.
     * @throws OperationCanceledException  If the cancellation token fires.
     */
    public function send(mixed $value, ?Completable $cancellationToken = null): void {}

    /**
     * Receive a value from the channel.
     *
     * Suspends the current coroutine until a value is available or the channel
     * is closed. Returns any buffered values before throwing on close.
     *
     * @param Completable|null $cancellationToken Optional cancellation token.
     * @return T The received value (copied into the current thread's heap).
     * @throws ThreadChannelException      If the channel is closed and empty.
     * @throws OperationCanceledException  If the cancellation token fires.
     */
    public function recv(?Completable $cancellationToken = null) {}

    /**
     * Close the channel.
     *
     * After closing:
     * - {@see send()} immediately throws {@see ThreadChannelException}.
     * - {@see recv()} drains remaining buffered values, then throws.
     * - All coroutines/threads suspended in send() or recv() are woken
     *   with {@see ThreadChannelException}.
     *
     * Calling close() more than once is a safe no-op.
     */
    public function close(): void {}

    /**
     * Return true if the channel has been closed.
     */
    public function isClosed(): bool {}

    /**
     * Return the channel capacity set at construction.
     */
    public function capacity(): int {}

    /**
     * Return the number of values currently buffered.
     */
    public function count(): int {}

    /**
     * Return true if no values are currently buffered.
     */
    public function isEmpty(): bool {}

    /**
     * Return true if the buffer is at capacity.
     */
    public function isFull(): bool {}
}
