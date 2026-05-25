<?php

declare(strict_types=1);

namespace Async;

/**
 * Concurrency primitive for typed message passing between coroutines.
 *
 * A Channel can be:
 *  - **unbuffered** (`capacity = 0`): rendezvous semantics — send blocks
 *    until a receiver is ready.
 *  - **buffered** (`capacity > 0`): bounded FIFO queue.
 *
 * @template T
 * @implements \IteratorAggregate<int, T>
 * @implements Awaitable<T>
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/channel.html
 */
final class Channel implements Awaitable, \IteratorAggregate, \Countable
{
    /**
     * Create a new Channel.
     *
     * @param int $capacity 0 = unbuffered; >0 = bounded buffer size.
     * @param int $noProducerTimeout Deadlock timeout (ms) while a receiver is
     *     blocked and no senders are queued. 0 = disabled (default).
     * @param int $noConsumerTimeout Deadlock timeout (ms) while a sender is
     *     blocked and no receivers are queued. 0 = disabled (default).
     * @param bool $hardTimeouts If false (default), the timer is hidden from
     *     the event loop (does not keep it alive); a global resolver may close
     *     the channel sooner. If true, the timer is a real event keeping the
     *     loop alive until it fires.
     */
    public function __construct(
        int $capacity = 0,
        int $noProducerTimeout = 0,
        int $noConsumerTimeout = 0,
        bool $hardTimeouts = false,
    ) {}

    /**
     * Send a value into the channel (blocking).
     *
     * Suspends the current coroutine until the value is consumed (unbuffered)
     * or until a buffer slot is available (buffered).
     *
     * @param T $value The value to send.
     * @param Completable|null $cancellationToken Optional cancellation token (e.g. timeout(ms)).
     *
     * @throws ChannelException If the channel is closed.
     * @throws OperationCanceledException If the cancellation token fires.
     */
    public function send(mixed $value, ?Completable $cancellationToken = null): void {}

    /**
     * Try to send a value without blocking.
     *
     * @param T $value The value to send.
     * @return bool True if the value was accepted; false if the channel is full or closed.
     */
    public function sendAsync(mixed $value): bool {}

    /**
     * Receive a value from the channel (blocking).
     *
     * Suspends the current coroutine until a value is available.
     *
     * @param Completable|null $cancellationToken Optional cancellation token (e.g. timeout(ms)).
     * @return T The received value.
     *
     * @throws ChannelException If the channel is closed and empty.
     * @throws OperationCanceledException If the cancellation token fires.
     */
    public function recv(?Completable $cancellationToken = null) {}

    /**
     * Receive a value without blocking.
     *
     * @return Future<T> Resolves to the next available value.
     */
    public function recvAsync(): Future {}

    /**
     * Close the channel.
     *
     * After closing:
     *  - {@see send()} throws {@see ChannelException}.
     *  - {@see recv()} drains remaining buffered values, then throws {@see ChannelException}.
     *  - All waiting coroutines are woken with {@see ChannelException}.
     */
    public function close(): void {}

    /**
     * Return true if the channel has been closed.
     */
    public function isClosed(): bool {}

    /**
     * Return the channel capacity (0 = unbuffered).
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

    /**
     * Return an iterator for foreach support.
     *
     * Yields each received value in order. Iteration ends when the channel
     * is closed and empty.
     *
     * @return \Iterator<int, T>
     */
    public function getIterator(): \Iterator {}
}
