<?php

declare(strict_types=1);

namespace Async;

/**
 * Interface for objects that represent a completable asynchronous operation.
 *
 * @template T
 * @extends Awaitable<T>
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/interfaces.html
 */
interface Completable extends Awaitable
{
    /**
     * Request cancellation of the operation.
     *
     * @param AsyncCancellation|null $cancellation Optional cancellation reason.
     */
    public function cancel(?AsyncCancellation $cancellation = null): void;

    /**
     * Return true if the operation has finished (successfully or with an error).
     */
    public function isCompleted(): bool;

    /**
     * Return true if the operation was cancelled.
     */
    public function isCancelled(): bool;
}
