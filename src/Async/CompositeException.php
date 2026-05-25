<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception that aggregates multiple exceptions.
 *
 * Used when several exceptions occur simultaneously, for example in finally handlers.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
final class CompositeException extends \Exception
{
    /** @var \Throwable[] */
    private array $exceptions = [];

    /**
     * Add an exception to the composite.
     */
    public function addException(\Throwable $exception): void {}

    /**
     * Get all aggregated exceptions.
     *
     * @return \Throwable[]
     */
    public function getExceptions(): array {}
}
