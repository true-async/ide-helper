<?php

declare(strict_types=1);

namespace Async;

/**
 * A one-shot cancellable timer that implements {@see Completable}.
 *
 * Obtain a Timeout via {@see timeout()}.
 *
 * @implements Completable<void>
 * @since 8.6
 */
final class Timeout implements Completable
{
    private function __construct() {}

    /** @inheritDoc */
    public function cancel(?AsyncCancellation $cancellation = null): void {}

    /** @inheritDoc */
    public function isCompleted(): bool {}

    /** @inheritDoc */
    public function isCancelled(): bool {}
}
