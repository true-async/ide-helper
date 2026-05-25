<?php

declare(strict_types=1);

namespace Async;

/**
 * Structured-concurrency scope that owns a group of coroutines.
 *
 * A Scope forms a parent–child hierarchy: when a scope is cancelled or
 * disposed, all coroutines it owns are cancelled as well.
 *
 * @since 8.6
 *
 * @see https://true-async.github.io/en/docs/components/scope.html
 */
final class Scope implements ScopeProvider
{
    /**
     * Create a new Scope that inherits from the given parent scope.
     *
     * If no parent is provided, the new scope inherits from the current one.
     *
     * @param Scope|null $parentScope
     * @return Scope
     */
    public static function inherit(?Scope $parentScope = null): Scope {}

    /** @inheritDoc */
    #[\Override]
    public function provideScope(): Scope {}

    /**
     * Create a new root Scope.
     */
    public function __construct() {}

    /**
     * Mark the scope as "not safely disposable" and return it.
     *
     * @return Scope $this
     */
    public function asNotSafely(): Scope {}

    /**
     * Spawn a new coroutine inside this scope.
     *
     * @template T
     * @param \Closure(mixed...): T $callable Coroutine body.
     * @param mixed     ...$params Arguments forwarded to the closure.
     * @return Coroutine<T> The new coroutine.
     */
    public function spawn(\Closure $callable, mixed ...$params): Coroutine {}

    /**
     * Cancel all coroutines owned by this scope.
     *
     * @param AsyncCancellation|null $cancellationError Optional cancellation reason.
     */
    public function cancel(?AsyncCancellation $cancellationError = null): void {}

    /**
     * Suspend the current coroutine until all child coroutines have finished.
     *
     * @param Awaitable $cancellation Cancellation token.
     * @throws OperationCanceledException If the cancellation token fires.
     */
    public function awaitCompletion(Awaitable $cancellation): void {}

    /**
     * Await scope completion after cancellation, optionally handling errors.
     *
     * @param callable|null $errorHandler Called for each unhandled child error.
     * @param Awaitable|null $cancellation Cancellation token.
     * @throws OperationCanceledException If the cancellation token fires.
     */
    public function awaitAfterCancellation(?callable $errorHandler = null, ?Awaitable $cancellation = null): void {}

    /**
     * Return true if all child coroutines have finished.
     */
    public function isFinished(): bool {}

    /**
     * Return true if the scope has been closed.
     */
    public function isClosed(): bool {}

    /**
     * Return true if the scope has been cancelled.
     */
    public function isCancelled(): bool {}

    /**
     * Set a handler invoked when a child coroutine propagates an unhandled exception.
     *
     * @param callable $exceptionHandler
     */
    public function setExceptionHandler(callable $exceptionHandler): void {}

    /**
     * Set an exception handler for child scopes.
     *
     * Setting this handler prevents the exception from propagating further up.
     *
     * @param callable $exceptionHandler
     */
    public function setChildScopeExceptionHandler(callable $exceptionHandler): void {}

    /**
     * Register a callback to be executed when the scope finishes.
     *
     * @param \Closure $callback
     */
    public function finally(\Closure $callback): void {}

    /**
     * Cancel and dispose of the scope immediately.
     */
    public function dispose(): void {}

    /**
     * Dispose of the scope, waiting for in-flight coroutines to finish gracefully.
     */
    public function disposeSafely(): void {}

    /**
     * Dispose of the scope after a timeout, even if coroutines have not finished.
     *
     * @param int $timeout Timeout in milliseconds.
     */
    public function disposeAfterTimeout(int $timeout): void {}

    /**
     * Return all direct child scopes.
     *
     * @return Scope[]
     */
    public function getChildScopes(): array {}
}
