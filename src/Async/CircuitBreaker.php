<?php

declare(strict_types=1);

namespace Async;

/**
 * Circuit breaker state machine.
 *
 * Manages state transitions for service availability.
 * This interface defines **how** to transition between states.
 * Use {@see CircuitBreakerStrategy} to define **when** to transition.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/pool.html
 */
interface CircuitBreaker
{
    /**
     * Get the current circuit breaker state.
     */
    public function getState(): CircuitBreakerState;

    /**
     * Transition to ACTIVE state (service available).
     */
    public function activate(): void;

    /**
     * Transition to INACTIVE state (service unavailable).
     */
    public function deactivate(): void;

    /**
     * Transition to RECOVERING state (probing for recovery).
     */
    public function recover(): void;
}
