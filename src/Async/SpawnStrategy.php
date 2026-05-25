<?php

declare(strict_types=1);

namespace Async;

/**
 * Strategy interface for controlling how coroutines are enqueued in a Scope.
 *
 * Implement this interface to customise scheduling behaviour before and after
 * a coroutine enters the run-queue.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/interfaces.html
 */
interface SpawnStrategy extends ScopeProvider
{
    /**
     * Called before the coroutine is enqueued.
     *
     * @param Coroutine $coroutine  The coroutine about to be enqueued.
     * @param Scope     $scope      The owning scope.
     * @return array<string, mixed> Arbitrary metadata passed to afterCoroutineEnqueue.
     */
    public function beforeCoroutineEnqueue(Coroutine $coroutine, Scope $scope): array;

    /**
     * Called immediately after the coroutine has been enqueued.
     *
     * @param Coroutine $coroutine The enqueued coroutine.
     * @param Scope     $scope     The owning scope.
     */
    public function afterCoroutineEnqueue(Coroutine $coroutine, Scope $scope): void;
}
