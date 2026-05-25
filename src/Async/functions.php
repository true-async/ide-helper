<?php

declare(strict_types=1);

namespace Async;

/**
 * Spawn a new coroutine in the current scope.
 *
 * @template T
 * @param callable(mixed...): T $task The coroutine body.
 * @param mixed    ...$args  Arguments forwarded to `$task`.
 * @return Coroutine<T> The newly created coroutine (already enqueued).
 * @see https://true-async.github.io/en/docs/reference/spawn.html
 * @since 8.6
 */
function spawn(callable $task, mixed ...$args): Coroutine {}

/**
 * Spawn a new coroutine using a custom {@see ScopeProvider}.
 *
 * @template T
 * @param ScopeProvider $provider Provides the target scope.
 * @param callable(mixed...): T $task     The coroutine body.
 * @param mixed         ...$args  Arguments forwarded to `$task`.
 * @return Coroutine<T>
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/spawn-with.html
 */
function spawn_with(ScopeProvider $provider, callable $task, mixed ...$args): Coroutine {}

/**
 * Spawn a new OS thread that runs the given closure.
 *
 * The child thread gets its own PHP runtime (TSRM). With OPcache enabled,
 * the parent's compiled code is shared via SHM (near-zero overhead).
 * Without OPcache, a deep copy is performed (TODO).
 *
 * ## Returning values from threads
 *
 * The idiomatic way to return a value from a thread is via {@see FutureState} —
 * the only Future-related object that can cross thread boundaries:
 * ```php
 * $state  = new FutureState();
 * $future = new Future($state);
 *
 * spawn_thread(function() use ($state) {
 *     $state->complete(computeResult());
 * });
 *
 * $result = await($future);
 * ```
 *
 * ## Transfer rules
 *
 * Arguments captured by the closure are deep-copied into the child thread.
 * Transferable types: scalars, arrays, objects with declared properties,
 * Closures, WeakReference, WeakMap, FutureState, ThreadChannel, ThreadPool.
 * Non-transferable: stdClass, PHP references, resources — these throw
 * {@see ThreadTransferException}.
 *
 * @template T
 * @param \Closure(): T $task       The closure to execute in the new thread.
 * @param bool          $inherit    If true (default), inherit the parent's function/class tables
 *                                  into the child thread. If false, only the closure and
 *                                  autoloaders are transferred.
 * @param \Closure|null $bootloader Optional closure executed in the thread before $task.
 *                                  Use it to set up autoloaders, initialize DI, etc.
 * @return Thread<T> A thread handle implementing Completable.
 * @throws ThreadTransferException If the closure captures non-transferable values.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/spawn-thread.html
 */
function spawn_thread(\Closure $task, bool $inherit = true, ?\Closure $bootloader = null): Thread {}

/**
 * Yield control to the scheduler, allowing other coroutines to run.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/suspend.html
 */
function suspend(): void {}

/**
 * Execute `$closure` in non-cancellable mode.
 *
 * Any cancellation requests are deferred until after the closure returns.
 *
 * @template T
 * @param \Closure(): T $closure
 * @return T The return value of `$closure`.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/protect.html
 */
function protect(\Closure $closure) {}

/**
 * Await the completion of a {@see Completable}.
 *
 * Suspends the current coroutine until `$awaitable` settles.
 *
 * @template T
 * @param Completable<T>      $awaitable
 * @param Completable|null $cancellation Optional cancellation token.
 * @return T The resolved value.
 * @throws \Throwable If `$awaitable` was rejected.
 * @throws OperationCanceledException If the cancellation token fires.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/await.html
 */
function await(Completable $awaitable, ?Completable $cancellation = null) {}

/**
 * Await the first trigger to settle; throw if it fails.
 *
 * @template TKey of array-key
 * @template TValue
 * @param iterable<TKey, Awaitable<TValue>> $triggers
 * @param Awaitable|null      $cancellation
 * @return TValue The value of the first settled trigger.
 * @throws \Throwable If the settled trigger was rejected.
 * @throws OperationCanceledException If the cancellation token fires.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/await-any-or-fail.html
 */
function await_any_or_fail(iterable $triggers, ?Awaitable $cancellation = null) {}

/**
 * Await the first trigger to succeed.
 *
 * Errors are skipped until a successful result is found.
 *
 * @template TKey of array-key
 * @template TValue
 * @param iterable<TKey, Awaitable<TValue>> $triggers
 * @param Awaitable|null      $cancellation
 * @return array{0: TValue, 1: array<TKey, \Throwable>} An array of [$result, $errors]. The first successful result.
 * @throws \Throwable If all triggers fail.
 * @throws OperationCanceledException If the cancellation token fires.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/await-first-success.html
 */
function await_first_success(iterable $triggers, ?Awaitable $cancellation = null): array {}

/**
 * Await all triggers; throw if any fails.
 *
 * @template TKey of array-key
 * @template TValue
 * @param iterable<TKey, Awaitable<TValue>> $triggers
 * @param Awaitable|null      $cancellation
 * @param bool                $preserveKeyOrder Preserve the original key order.
 * @return array<TKey, TValue> Resolved values.
 * @throws \Throwable On the first failed trigger.
 * @throws OperationCanceledException If the cancellation token fires.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/await-all-or-fail.html
 */
function await_all_or_fail(iterable $triggers, ?Awaitable $cancellation = null, bool $preserveKeyOrder = true): array {}

/**
 * Await all triggers, collecting both results and errors.
 *
 * @template TKey of array-key
 * @template TValue
 * @param iterable<TKey, Awaitable<TValue>> $triggers
 * @param Awaitable|null      $cancellation
 * @param bool                $preserveKeyOrder
 * @param bool                $fillNull         Fill failed slots with null instead of skipping.
 * @return array{0: array<TKey, TValue>, 1: array<TKey, \Throwable>} An array of [$results, $errors].
 * @throws OperationCanceledException If the cancellation token fires.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/await-all.html
 */
function await_all(iterable $triggers, ?Awaitable $cancellation = null, bool $preserveKeyOrder = true, bool $fillNull = false): array {}

/**
 * Await exactly `$count` triggers; throw if fewer than `$count` succeed.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @param int                 $count
 * @param iterable<TKey, Awaitable<TValue>> $triggers
 * @param Awaitable|null      $cancellation
 * @param bool                $preserveKeyOrder
 * @return array<TKey, TValue>
 * @throws \Throwable If fewer than `$count` triggers succeed.
 * @throws OperationCanceledException If the cancellation token fires.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/await-any-of-or-fail.html
 */
function await_any_of_or_fail(int $count, iterable $triggers, ?Awaitable $cancellation = null, bool $preserveKeyOrder = true): array {}

/**
 * Await up to `$count` triggers, collecting results without throwing.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @param int                 $count
 * @param iterable<TKey, Awaitable<TValue>> $triggers
 * @param Awaitable|null      $cancellation
 * @param bool                $preserveKeyOrder
 * @param bool                $fillNull
 * @return array{0: array<TKey, TValue>, 1: array<TKey, \Throwable>} An array of [$results, $errors].
 * @throws OperationCanceledException If the cancellation token fires.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/await-any-of.html
 */
function await_any_of(int $count, iterable $triggers, ?Awaitable $cancellation = null, bool $preserveKeyOrder = true, bool $fillNull = false): array {}

/**
 * Suspend the current coroutine for at least `$ms` milliseconds.
 *
 * @param int $ms Delay in milliseconds.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/delay.html
 */
function delay(int $ms): void {}

/**
 * Create a {@see Timeout} that fires after `$ms` milliseconds.
 *
 * @param int $ms
 * @return Timeout A Timeout instance.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/timeout.html
 */
function timeout(int $ms): Timeout {}

/**
 * Return the current coroutine's context.
 *
 * @return Context
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/current-context.html
 */
function current_context(): Context {}

/**
 * Return the context of the root coroutine in the current coroutine hierarchy.
 *
 * @return Context
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/coroutine-context.html
 */
function coroutine_context(): Context {}

/**
 * Return the currently executing coroutine.
 *
 * @return Coroutine
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/current-coroutine.html
 */
function current_coroutine(): Coroutine {}

/**
 * Return the root context of the scheduler.
 *
 * @return Context
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/root-context.html
 */
function root_context(): Context {}

/**
 * Return the Context of the request-level Scope inherited from the spawning
 * coroutine, or null if none is set. The request scope is assigned by the
 * embedding C code (e.g. an HTTP server) and propagates to all child
 * coroutines automatically.
 *
 * @return Context|null
 * @since 8.6
 */
function request_context(): ?Context {}

/**
 * Return a snapshot of all live coroutines managed by the scheduler.
 *
 * @return Coroutine[]
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/get-coroutines.html
 */
function get_coroutines(): array {}

/**
 * Iterate over `$iterable`, invoking `$callback` for each element.
 *
 * The callback receives `(value, key)` and may return `false` to stop
 * iteration. Blocks the current coroutine until all iterations complete.
 *
 * If `$cancelPending` is true (default), coroutines spawned inside the
 * callback are cancelled when iteration finishes. If false, the function
 * waits for all spawned coroutines to complete.
 *
 * @template TKey
 * @template TValue
 * @param iterable<TKey, TValue> $iterable
 * @param callable(TValue, TKey): void $callback     `fn(mixed $value, mixed $key): mixed`
 * @param int       $concurrency  Maximum concurrent callback invocations; 0 = unlimited.
 * @param bool      $cancelPending Cancel pending coroutines when done.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/iterate.html
 */
function iterate(iterable $iterable, callable $callback, int $concurrency = 0, bool $cancelPending = true): void {}

/**
 * Initiate a graceful shutdown of the scheduler.
 *
 * @param AsyncCancellation|null $cancellationError Optional cancellation reason propagated to running coroutines.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/graceful-shutdown.html
 */
function graceful_shutdown(?AsyncCancellation $cancellationError = null): void {}

/**
 * Await the delivery of an OS signal.
 *
 * Returns a Future that resolves with the {@see Signal} enum case when the
 * specified signal is received.
 *
 * @param Signal           $signal
 * @param Completable|null $cancellation
 * @return Future<Signal>
 * @throws OperationCanceledException If the cancellation token fires.
 * @since 8.6
 * @see https://true-async.github.io/en/docs/reference/signal.html
 */
function signal(Signal $signal, ?Completable $cancellation = null): Future {}

/**
 * Compute CPU usage since the previous call, with percentages already derived.
 * Convenient for telemetry loops.
 *
 * The function keeps a per-process internal "previous" snapshot. The first call
 * stores the snapshot and returns zeros; every subsequent call returns the
 * delta against the previously stored snapshot and replaces it.
 *
 * Returns:
 *   [
 *     'process_cores'   => float, // 0..cpuCount, multi-core factor
 *     'process_percent' => float, // 0..100, share of total machine capacity
 *     'system_percent'  => float, // 0..100, total host CPU utilisation
 *     'cpu_count'       => int,
 *     'interval_sec'    => float, // wall-clock duration between snapshots
 *     'loadavg'         => array{0:float,1:float,2:float}|null, // null on Windows
 *   ]
 *
 * Note: state is global per process. If you need multiple independent
 * telemetry consumers, take {@see CpuSnapshot::now()} snapshots and compute
 * deltas yourself.
 *
 * @return array<string, mixed>
 */
function cpu_usage(): array {}

/**
 * Returns the system load averages over the last 1, 5, and 15 minutes,
 * or null if the platform does not provide load average (Windows).
 *
 * Load average is the average length of the kernel run-queue, which is a
 * different metric from CPU utilisation. On a 4-core machine a sustained
 * load of 4.0 means the run-queue is, on average, fully populated.
 *
 * @return array{0:float,1:float,2:float}|null
 */
function loadavg(): ?array {}

/**
 * Returns the number of CPUs available to the current process.
 *
 * Honours cgroup CPU quotas, sched_setaffinity, and similar limits — the
 * value libuv recommends for thread-pool / worker sizing. Always >= 1.
 */
function available_parallelism(): int {}
