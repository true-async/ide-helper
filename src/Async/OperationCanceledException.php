<?php

declare(strict_types=1);

namespace Async;

/**
 * Exception thrown when an awaited operation is cancelled by a cancellation token.
 *
 * This exception wraps the original reason from the token as `$previous`,
 * allowing the caller to distinguish a token-triggered cancellation from
 * an exception thrown by the awaitable itself.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class OperationCanceledException extends AsyncCancellation {}
