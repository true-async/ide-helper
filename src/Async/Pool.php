<?php

declare(strict_types=1);

namespace Async;

/**
 * Generic resource pool with automatic lifecycle management.
 *
 * Resources circulate between an idle buffer and active usage. Implements
 * {@see CircuitBreaker} for service-availability control.
 *
 * @template TResource
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/pool.html
 */
final class Pool implements \Countable, CircuitBreaker
{
    /**
     * Create a new resource pool.
     *
     * @param callable(): TResource $factory             Creates a new resource: `fn(): mixed`
     * @param callable(TResource): void|null $destructor          Destroys a resource: `fn(mixed $resource): void`
     * @param callable(TResource): bool|null $healthcheck         Background health check: `fn(mixed $resource): bool`
     * @param callable|null $beforeAcquire       Pre-acquire check: `fn(mixed $resource): bool`
     *                                           (false = destroy and fetch next)
     * @param callable|null $beforeRelease       Pre-release hook: `fn(mixed $resource): bool`
     *                                           (false = destroy instead of returning to pool)
     * @param int           $min                 Minimum idle resources pre-created on startup.
     * @param int           $max                 Maximum total resources (idle + active).
     * @param int           $healthcheckInterval Background health-check interval in ms; 0 = disabled.
     */
    public function __construct(
        callable $factory,
        ?callable $destructor = null,
        ?callable $healthcheck = null,
        ?callable $beforeAcquire = null,
        ?callable $beforeRelease = null,
        int $min = 0,
        int $max = 10,
        int $healthcheckInterval = 0,
    ) {}

    /**
     * Acquire a resource, blocking until one is available.
     *
     * @param int $timeout Max wait time in ms; 0 = infinite.
     * @return TResource The acquired resource.
     * @throws PoolException If the pool is closed or the timeout expires.
     * @throws ServiceUnavailableException
     */
    public function acquire(int $timeout = 0) {}

    /**
     * Try to acquire a resource without blocking.
     *
     * @return TResource|null The resource, or null if none is immediately available.
     * @throws PoolException If the pool is closed or the timeout expires.
     * @throws ServiceUnavailableException
     */
    public function tryAcquire() {}

    /**
     * Release a resource back to the pool.
     *
     * If `$beforeRelease` returns false, the resource is destroyed instead.
     *
     * @param TResource $resource The resource to release.
     * @throws PoolException If the pool is closed or the timeout expires.
     * @throws ServiceUnavailableException
     */
    public function release(mixed $resource): void {}

    /**
     * Close the pool and destroy all resources.
     *
     * All waiting coroutines are woken with {@see PoolException}.
     */
    public function close(): void {}

    /**
     * Return true if the pool has been closed.
     */
    public function isClosed(): bool {}

    /**
     * Return the total resource count (idle + active).
     */
    public function count(): int {}

    /**
     * Return the number of idle (available) resources.
     */
    public function idleCount(): int {}

    /**
     * Return the number of active (in-use) resources.
     */
    public function activeCount(): int {}

    /**
     * Attach a circuit breaker strategy to control service availability.
     *
     * @param CircuitBreakerStrategy|null $strategy
     */
    public function setCircuitBreakerStrategy(?CircuitBreakerStrategy $strategy): void {}

    /** @inheritDoc */
    public function getState(): CircuitBreakerState {}

    /** @inheritDoc */
    public function activate(): void {}

    /** @inheritDoc */
    public function deactivate(): void {}

    /** @inheritDoc */
    public function recover(): void {}
}
