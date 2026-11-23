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
     * Create a root Scope: no parent Scope stands above it.
     *
     * Context lookup from inside stops here, so {@see current_context()}->find() never
     * reaches the main Scope and {@see request_context()} returns null; {@see root_context()}
     * still returns the main Scope's Context. Disposal is not safe by default: coroutines
     * still running when the Scope is disposed are cancelled, until {@see allowZombies()}
     * opts back in. Use {@see inherit()} for a Scope that continues the current one.
     */
    public function __construct() {}

    /**
     * Mark the scope as not safely disposable and return it: coroutines outliving the
     * scope are cancelled instead of turning into zombies.
     *
     * @return Scope $this
     */
    public function asNotSafely(): Scope {}

    /**
     * Opt the scope into safe disposal and return it: coroutines outliving the scope
     * become zombies instead of being cancelled.
     *
     * @return Scope $this
     */
    public function allowZombies(): Scope {}

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
     * Return true once the Scope has finished its work: it is cancelled or closed, or no active coroutine is left
     * in it or in its child scopes. Zombie coroutines (see {@see disposeSafely()}) do not count.
     *
     * Right after cancel(), dispose() or disposeSafely() this is true even while a coroutine of the Scope still
     * runs, for example cleaning up after catching its cancellation. To wait until the last coroutine has ended,
     * use {@see awaitAfterCancellation()}.
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
     * Dispose of the scope immediately.
     *
     * Coroutines still running are cancelled, unless the Scope carries the
     * dispose-safely flag (set by {@see allowZombies()} or inherited from a
     * parent that has it, such as the main Scope); then they are left to
     * finish as zombies, exactly as {@see disposeSafely()} does.
     */
    public function dispose(): void {}

    /**
     * Close the scope without cancelling its coroutines.
     *
     * It does not wait: coroutines still running are left to finish as zombies, outside the Scope.
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
