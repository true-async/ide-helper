<?php

declare(strict_types=1);

namespace Async;

/**
 * Error thrown when a deadlock is detected.
 *
 * @since 8.6
 * @see https://true-async.github.io/en/docs/components/exceptions.html
 */
class DeadlockError extends \Error {}
