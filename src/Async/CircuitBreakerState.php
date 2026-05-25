<?php

declare(strict_types=1);

namespace Async;

/**
 * Circuit breaker states Async/{@see Pool}
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/pool.html
 */
enum CircuitBreakerState
{
    /**
     * Service is working normally.
     * All requests are allowed through.
     */
    case ACTIVE;

    /**
     * Service is unavailable.
     * {@see Pool::acquire()} throws {@see PoolException}
     */
    case INACTIVE;

    /**
     * Test mode.
     * Limited requests are allowed through.
     */
    case RECOVERING;
}
