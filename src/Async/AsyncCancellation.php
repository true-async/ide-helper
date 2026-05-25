<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception thrown when a Coroutine is canceled.
 *
 * Code inside the Coroutine must properly handle this exception
 * to ensure graceful termination.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class AsyncCancellation extends \Exception implements \Cancellation {}
