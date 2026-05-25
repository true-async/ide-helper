<?php

declare(strict_types=1);

namespace Async;

/**
 * Circuit breaker strategy interface.
 *
 * Defines **when** to transition between circuit breaker states.
 * Implement this interface to create custom failure-detection logic.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/pool.html
 */
interface CircuitBreakerStrategy
{
    /**
     * Called when an operation succeeds.
     *
     * @param mixed $source The object reporting the event (e.g., {@see Pool}).
     */
    public function reportSuccess(mixed $source): void;

    /**
     * Called when an operation fails.
     *
     * @param mixed      $source The object reporting the event (e.g., {@see Pool}).
     * @param \Throwable $error  The error that occurred.
     */
    public function reportFailure(mixed $source, \Throwable $error): void;

    /**
     * Check if the circuit should attempt to recover.
     *
     * Called periodically while the circuit is INACTIVE to determine
     * whether it should transition to RECOVERING.
     */
    public function shouldRecover(): bool;
}
